<?php

namespace App\Controller;

use App\Entity\Standing;
use App\Form\StandingType;
use App\Repository\StandingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class StandingController extends AbstractController
{    
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/standing_liste', name: 'app.standing.liste')]
    public function index(StandingRepository $standingRepository,
     PaginatorInterface $paginator, Request $request): Response
    {

       $standings = $paginator -> paginate(
        $standingRepository->findAll(),
        $request ->query->getInt('Page',1),
        5
       ) ; 
        return $this->render('admin/standing/index_standing.html.twig', [
            'standings' => $standings,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ajout/standing', name: 'app.standing.new', methods: ['GET', 'POST'])]
    public function standing(Request $request, EntityManagerInterface $manager ) : Response
    {

       $standing = new Standing();
       $form = $this ->createForm(StandingType::class, $standing);
       $form->handleRequest($request);
       
       if ($form->isSubmitted() && $form->isValid()) { 

        $standing = $form ->getData();
    

    $manager->persist($standing);
    $manager->flush();

    return $this->redirectToRoute('app.standing.liste');
       }

       return $this->render('admin/standing/new.html.twig', [
        'form'=>$form->createView()
    ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/standing_edit', name:'app.standing.edit')]
    public function edit(Standing $standing ,
    Request $request, EntityManager $manager ): Response
    {
      $form = $this->createForm(standingType::class, $standing);
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) { 
        $standing =$form->getData();

        $manager->persist($standing);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le standing a été modifié avec succès'
        );

        return $this->redirectToRoute('app.standing.liste');
        
      }
        return $this->render('admin/edit_standing.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}

