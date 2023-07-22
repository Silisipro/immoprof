<?php

namespace App\Controller;

use App\Form\LogeType;
use App\Entity\TypeBien;
use App\Repository\TypeBienRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TypeBienController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/type/bien/liste', name: 'app.typebien.list', methods: ['GET', 'POST'])]
    public function index(TypeBienRepository $Repository,
     PaginatorInterface $paginator, Request $request ): Response
    {
            
            $typebiens = $paginator->paginate (
            $Repository->findAll(),
            $request ->query->getInt('Page',1),
            10
            );

        return $this->render('admin/typebien/index.html.twig', [
            'typebiens' => $typebiens,
        ]);
    }
     

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ajout/typebien', name: 'app.typebien.new', methods: ['GET', 'POST'])]
    public function typebien(Request $request, EntityManagerInterface $manager) : Response
    {
         
       $typebien = new TypeBien();
       $form = $this ->createForm(LogeType::class, $typebien);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) { 
        $data = [
            'chois' => $form->get('chois')->getData()];
            foreach ($data as $d) 
             $typebien = $form ->getData();
             $typebien->setCategorie($d);

            $manager->persist($typebien);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le type de bien a été ajouté avec succès'
            );

            return $this->redirectToRoute('app.typebien.list');      
       }


       return $this->render('admin/typebien/new.html.twig', [
        'form'=>$form->createView()
    ]);

   }


    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/edit/typebien/{id}', name: 'app.typebien.edit', methods: ['GET', 'POST'])]
    public function edittypbien(TypeBien $typeBien, 
    Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(LogeType::class, $typeBien);
        $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
            $data = [
                'chois' => $form->get('chois')->getData()];
                foreach ($data as $d) 
                 $typebien = $form ->getData();
                 $typebien->setCategorie($d);

            $manager->persist($typeBien);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le type du bien a été modifié avec succès'
            );

            return $this->redirectToRoute('app.typebien.list');
         }

        return $this->render('admin/typebien/edit.html.twig', [
            'form'=>$form->createView()
            
        ]);
    }

    #[ROUTE ('/favori/louer', name:'app_favori_louer')]
    public function louer(TypeBienRepository $typeBienRepository) : Response

    {
        $favoris = $typeBienRepository->findBy(
            [
                'categorie'=>'a_louer',
                'deleted'=> false,
                'favori'=> true,
            ],
            [
                'type'=> 'ASC'
            ]
        );

        return $this->render('type_bien_favori/louer.html.twig',[
            'favoris'=> $favoris
        ]);
    }

    #[ROUTE ('/favori/vendre', name:'app_favori_vendre')]
    public function vendre(TypeBienRepository $typeBienRepository) : Response

    {
        $favoris = $typeBienRepository->findBy(
            [
                'categorie'=>'a_vendre',
                'deleted'=> false,
                'favori'=> true,
            ],
            [
                'type'=> 'ASC'
            ]
        );

        return $this->render('type_bien_favori/vendre.html.twig',[
            'favoris'=> $favoris
        ]);
    }


}
