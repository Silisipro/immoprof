<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
/**
  * This controller display all bien
  * @param BienRepository $bienRepository
  * @return Response
  */

    #[Route('/admin/bien', name: 'app.admin.bien')]
    public function index(BienRepository $bienRepository): Response
    {

        $biens=$bienRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'biens' => $biens,
        ]);
    }
 
    #[Route('/admin/editbien/{id}', name: 'app.admin.edit')]
    public function edit(Bien $bien, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) { 
            $bien =$form->getData();

            $manager->persist($bien);
            $manager->flush();
         }

        return $this->render('admin/edit.html.twig', [
            
        ]);
    }





}
