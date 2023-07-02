<?php

namespace App\Controller\Admin\admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\EmailSmsServices;
use App\Service\RandomStringGeneratorServices;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(private FileUploader $fileUploader, private RandomStringGeneratorServices $randomStringGeneratorServices)
    {
    }

    #[Route("/dashbord",name:"admin_index", methods:['GET']) ]
    public function index() : Response
    {
     return $this->render('admin/bien/indexdashbord.html.twig',[
             
     ]);
    } 

/**
  * This controller display all bien
  * @param BienRepository $bienRepository
  * @return Response
  */
    #[IsGranted('ROLE_USER')]
    #[Route('/bien', name: 'app.admin.bien', methods: ['GET', 'POST'])]
    public function listebien(BienRepository $bienRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $biens = null;
        
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CHEF_PROJET')) {
            $biens = $paginator->paginate(
                $bienRepository->findBy(
                [
                    'deleted' => false,
                ],
                [
                    'createdAt' => 'DESC',
                ]
                ),
                $request->query->getInt('page', 1),
                5
            );
        } else {
            
            $biens = $paginator->paginate(
            $bienRepository->findBy(
                [
                    'deleted' => false,
                    'user' => $this->getUser(),
                ],
                [
                    'createdAt' => 'DESC',
                ]
                ),
                $request->query->getInt('page', 1),
                5
            );
        }

        return $this->render('admin/bien/index.html.twig', [
            'biens' => $biens,
        ]);
    }
 
    #[Route('/creation/bien', name: 'app.bien.new', methods: ['GET', 'POST'])]
   public function new (Request $request, 
   EntityManagerInterface $manager,
   ): Response
{
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $reference = $this->randomStringGeneratorServices->random_alphanumeric(7);
                foreach ($request->files as $key => $file) {
                    if ($key !== 'bien') {
                        if ($file instanceof UploadedFile) $this->fileUploader->saveFile($file, false, Bien::class, null, $reference);
                    }
                }
                $bien->setCodeFichier($reference);
                $bien = $form ->getData();

                $bien->setUser($this->getUser());
                $typeBienLouer = $form->get('typeBienLouer')->getData();
                $typeBienVendre = $form->get('typeBienVendre')->getData();
                if ($typeBienLouer !== null) {
                    $bien->setTypeBien($typeBienLouer);
                }
                if ($typeBienVendre !== null) {
                    $bien->setTypeBien($typeBienVendre);
                }

                if ($this->isGranted('ROLE_ADMIN')) {
                    $bien->setEtat('en attente de publication');
                    $bien->setDatePublication(new \DateTime());
                } else {
                    $bien->setEtat('en attente de publication');
                }
            $manager->persist($bien);
            $manager->flush();
        
            $this->addFlash(
            'success',
            ' Votre bien a été ajouté avec succès'
        );


            return $this->redirectToRoute('app.admin.bien');  
        };

       return $this->render('admin/bien/new.html.twig', [
           'form'=>$form->createView()
       ]);
   }
    #[Security("is_granted('ROLE_USER','ROLE_ADMIN') || user===bien.getUser()")]
    #[Route('/edit/bien/{id}', name: 'app.admin.edit', methods: ['GET', 'POST'])]
    public function edit(Bien $bien, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
            $reference = $bien->getCodeFichier();
            foreach ($request->files as $key => $file) {
                if ($key !== 'bien') {
                    if ($file instanceof UploadedFile) $this->fileUploader->saveFile($file, false, Bien::class, null, $reference);
                }
            }
            $bien->setUpdatedAt(new \DateTimeImmutable());
            $bien =$form->getData();
            if ($this->isGranted('ROLE_ADMIN')  || $this->isGranted('ROLE_CHEF_PROJET')) {
                $bien->setEtat('en attente de publication');
                $bien->setDatePublication(new \DateTime());
            } else {
                $bien->setEtat('en attente de publication');
            }
            $manager->persist($bien);
            $manager->flush();

            $this->addFlash(
                'success',
                ' Votre bien a été modifié avec succès'
            );

            return $this->redirectToRoute('app.admin.bien');
         };

        return $this->render('admin/bien/edit.html.twig', [
            'form'=>$form->createView()
            
        ]);
    }

    #[Route('/publier/{id}', name: 'app.publier.bien', methods: ['POST'])]
    public function publierBien(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('publier'.$bien->getId(), $request->request->get('_token'))) {
            $bien->setEtat('publie');
            $bienRepository->save($bien, true);

            $this->addFlash(
                'success',
                ' Votre bien a été publié avec succès'
            );
        }
        if (json_encode($bien->getUser()->getRoles()) == '["ROLE_CHEF_PROJET"]') {
            return $this->redirectToRoute('publie.par.tout.user');
        } else {
            return $this->redirectToRoute('app.admin.bien');
        }
    }

    #[Route('/supression/bien/{id}', name: 'app.admin.delete', methods: ['POST'])]
    public function delete(Bien  $bien, Request $request, BienRepository $bienRepository ) : Response
    { 
        if($this->isCsrfTokenValid ('delete'.$bien->getId(), $request->request->get('_token'))) {
            $bien->setDeleted(true);
            $bienRepository->remove($bien, true);
        }
        
        $this->addFlash(
            'success',
            ' Votre bien a été suprimé avec succès'
        );

        return $this->redirectToRoute('app.admin.bien'); 
    }  
   
    #[Route('/publie-par-moi-meme', name: 'publie.par.user.meme', methods: ['GET'])]
    public function publieParuser(BienRepository $bienRepository): Response
    {
        $listeBiens = $bienRepository->findBy(
            [
                'deleted' => false,
                'etat' => 'publie',
                'user' => $this->getUser(),
            ],
            [
                'datepublication' => 'DESC',
            ]
        );
        return $this->render('admin/bien/publie_moi_meme.html.twig', [
            'biens' => $listeBiens,
        ]);
    }
  
    #[Route('/en-attente-de-publication', name: 'en.attente.de.publication', methods: ['GET'])]
    public function biensEnAttenteDePublication(BienRepository $bienRepository): Response
    {
        $listeBiens = null;
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CHEF_PROJET')) {
            $listeBiens = $bienRepository->findBy(
                [
                    'deleted' => false,
                    'etat' => 'en attente de publication',
                ],
                [
                    'createdAt' => 'DESC',
                ]
            );
        } else {
            //  rôle USER
            $listeBiens = $bienRepository->findBy(
                [
                    'deleted' => false,
                    'etat' => 'en attente de publication',
                    'user' => $this->getUser(),
                ],
                [
                    'createdAt' => 'DESC',
                ]
            );
        }
        return $this->render('admin/bien/bien_en_attente_publication.html.twig', [
            'biens' => $listeBiens,
        ]);
    }

    #[Route('/publie-par-tout-user', name: 'publie.par.tout.user', methods: ['GET'])]
    public function publieParTout(BienRepository $bienRepository): Response
    {
        $listeBiens = null;
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CHEF_PROJET')) {
            $listeBiens = $bienRepository->biensPubliesParToutuser('publie');
              
        } else {
            //  rôle USER
            $listeBiens = $bienRepository->findBy(
                [
                    'deleted' => false,
                    'etat' => 'publie',
                    'user' => $this->getUser(),
                ],
                [
                    'datePublication' => 'DESC',
                ]
            );
        }
        return $this->render('admin/bien/publie_par_tout_user.html.twig', [
            'biens' => $listeBiens,
        ]);
    }
   
    #[Route('/republier/{id}', name: 'app.republier.bien', methods: ['POST'])]
    public function republierBien(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('republier'.$bien->getId(), $request->request->get('_token'))) {
            $bien->setEtat('en attente de publication');
            $bienRepository->save($bien, true);
        }
        return $this->redirectToRoute('en.attente.de.publication');
    }
 
    #[Route('/louer/{id}', name: 'app_louer_bien', methods: ['POST'])]
    public function louerBien(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('louer'.$bien->getId(), $request->request->get('_token'))) {
            $bien->setEtat('loue');
            $bien->setDateLocationVente(new \DateTime());
            $bienRepository->save($bien, true);
        }
        if (is_null($bien->getUser())) {
            return $this->redirectToRoute('loue_vendu_par_moi_meme');
        }
        if (json_encode($bien->getUser()->getRoles()) == '["ROLE_USER"]') {
            return $this->redirectToRoute('loue_vendu_par_user');
        } else {
            return $this->redirectToRoute('loue_vendu_par_moi_meme');
        }
    }


    #[Route('/vendre/{id}', name: 'app_vendre_bien', methods: ['POST'])]
    public function vendreBien(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('vendre'.$bien->getId(), $request->request->get('_token'))) {
            $bien->setEtat('vendu');
            $bien->setDateLocationVente(new \DateTime());
            $bienRepository->save($bien, true);
        }
        if (is_null($bien->getUser())) {
            return $this->redirectToRoute('loue_vendu_par_moi_meme');
        }
        if (json_encode($bien->getUser()->getRoles()) == '["ROLE_USER"]') {
            return $this->redirectToRoute('loue_vendu_par_user');
        } else {
            return $this->redirectToRoute('loue_vendu_par_moi_meme');
        }
    }


    #[Route('/loue-vendu-par-moi-meme', name: 'loue_vendu_par_moi_meme', methods: ['GET'])]
    public function loueVenduParMoiMeme(BienRepository $bienRepository): Response
    {
        $listeBiens = null;
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CHEF_PROJET')) {
            $listeBiens = $bienRepository->biensLoueVendu('loue','vendu');
        }
        return $this->render('admin/bien/loue_vendu_moi_meme.html.twig', [
            'biens' => $listeBiens,
        ]);
    }


    #[Route('/loue-vendu-par-user', name: 'loue_vendu_par_user', methods: ['GET'])]
    public function loueVenduPaUser(BienRepository $bienRepository): Response
    {
        $listeBiens = null;
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_CHEF_PROJET')) {
            $listeBiens = $bienRepository->VendLoue('loue','vendu');
        } else {
           
            $listeBiens = $bienRepository->VendLoueParUser('loue','vendu', $this->getUser());
        }
        return $this->render('admin/bien/loue_vendu_par_user.html.twig', [
            'biens' => $listeBiens,
        ]);
    }








}
