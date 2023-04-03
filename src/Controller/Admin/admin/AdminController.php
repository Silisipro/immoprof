<?php

namespace App\Controller\Admin\admin;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;




class AdminController extends AbstractController
{
/**
  * This controller display all bien
  * @param BienRepository $bienRepository
  * @return Response
  */

    #[Route('/admin/bien', name: 'app.admin.bien', methods: ['GET', 'POST'])]
    public function index(BienRepository $bienRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $biens = $paginator->paginate(
         $bienRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/index.html.twig', [
            'biens' => $biens,
        ]);
    }
 
/**
    * This controller create new bien
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[Route('/admin/creation/bien', name: 'app.bien.new', methods: ['GET', 'POST'])]
   public function new (Request $request, EntityManagerInterface $manager ): Response
   {
               $bien = new Bien();
               $form = $this->createForm(BienType::class, $bien);
               $form->handleRequest($request);
                   if ($form->isSubmitted() && $form->isValid()) {
                       $recipe = $form ->getData();

                   $manager->persist($bien);
                   $manager->flush();
               
                   $this->addFlash(
                    'success',
                    ' Votre bien a été ajouté avec succès'
                );


                   return $this->redirectToRoute('app.admin.bien');   
               };

       return $this->render('admin/new.html.twig', [
           'form'=>$form->createView()
       ]);
   }


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

        return $this->render('admin/edit.html.twig', [
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


        return $this->redirectToRoute('admin/index.html.twig');   
    }  
    


}
