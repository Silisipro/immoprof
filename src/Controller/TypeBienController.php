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
    public function index(TypeBienRepository $Repository, PaginatorInterface $paginator, Request $request ): Response
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
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/edit/typebien/{id}', name: 'app.typebien.edit', methods: ['GET', 'POST'])]
    public function edittypbien(TypeBien $typeBien, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(LogeType::class, $typeBien);
        $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
            $typeBien =$form->getData();

            $manager->persist($typeBien);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le type du bien a été modifié avec succès'
            );

            return $this->redirectToRoute('app.typebien.list');
         };

        return $this->render('admin/typebien/edit.html.twig', [
            'form'=>$form->createView()
            
        ]);
    }


    










}
