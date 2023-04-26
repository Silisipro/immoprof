<?php

namespace App\Controller\Admin\admin;

use App\Entity\Bien;
use App\Entity\Standing;
use App\Entity\TypeBien;
use App\Form\BienType;
use App\Form\LogeType;
use App\Form\StandingType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


class AdminController extends AbstractController
{
/**
  * This controller display all bien
  * @param BienRepository $bienRepository
  * @return Response
  */
    #[IsGranted('ROLE_USER')]
    #[Route('/admin/bien', name: 'app.admin.bien', methods: ['GET', 'POST'])]
    public function index(BienRepository $bienRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $biens = $paginator->paginate(
            $bienRepository->findBy(['user'=> $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/bien/index.html.twig', [
            'biens' => $biens,
        ]);
    }
 
/**
    * This controller create new bien
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[IsGranted('ROLE_USER')]
    #[Route('/admin/creation/bien', name: 'app.bien.new', methods: ['GET', 'POST'])]
   public function new (Request $request, EntityManagerInterface $manager ): Response
   {
               $bien = new Bien();
               $form = $this->createForm(BienType::class, $bien);
               $form->handleRequest($request);
                   if ($form->isSubmitted() && $form->isValid()) {
                       $bien = $form ->getData();
                       $bien->setUser($this->getUser());


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

    #[Security("is_granted('ROLE_USER') and user===bien.getUser()")]
    #[Route('/admin/edit/bien/{id}', name: 'app.admin.edit', methods: ['GET', 'POST'])]
    public function edit(Bien $bien, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
            $bien =$form->getData();

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

     /**
    * This controller delete bien
    * @param bien  $bien
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[IsGranted('ROLE_USER')]
    #[Route('/admin/supression/bien/{id}', name: 'app.admin.delete', methods:['GET'])]
    public function delete(Bien  $bien, Request $request, EntityManagerInterface $manager ) : Response
    { 
        # if($this->isCsrfTokenValid('delete' . $bien->getId(), $request->get('_token'))) {
        #    $manager->remove($bien);
        #    $manager->flush();
        # }
        $manager->remove($bien);
        $manager->flush();
        $this->addFlash(
            'success',
            ' Votre ingrédient a été suprimé avec succès'
        );


        return $this->redirectToRoute('admin/bien/index.html.twig'); 
    }  
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/ajout/standing', name: 'app.standing.new', methods: ['GET', 'POST'])]
    public function standing(Request $request, EntityManagerInterface $manager )
    {

       $standing = new Standing();
       $form = $this ->createForm(StandingType::class, $standing);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) { 

        $standing = $form ->getData();
    

    $manager->persist($standing);
    $manager->flush();


            return $this->redirectToRoute('app.bien.new');      
       };

       return $this->render('admin/standing/new.html.twig', [
        'form'=>$form->createView()
    ]);

    }

     
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/ajout/typebien', name: 'app.typebien.new', methods: ['GET', 'POST'])]
    public function typebien(Request $request, EntityManagerInterface $manager )
    {

       $typebien = new TypeBien();
       $form = $this ->createForm(LogeType::class, $typebien);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) { 

        $typebien = $form ->getData();
    


    $manager->persist($typebien);
    $manager->flush();


            return $this->redirectToRoute('app.admin.bien');      
       };



       return $this->render('admin/typebien/new.html.twig', [
        'form'=>$form->createView()
    ]);

    }

}





