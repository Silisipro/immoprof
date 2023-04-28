<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\BienRecherche;
use App\Form\BienRechercheType;
use App\Repository\BienRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    
    #[Route('/bien/disponible', name: 'bien.tout.index')]
    public function index(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
     {

       $recherche= new BienRecherche();
       $form = $this->createForm(BienRechercheType::class, $recherche);
       $form->handleRequest($request);

       $biens= $paginator->paginate(
        $bienRepository->findVisible($recherche),
        $request->query->getInt('page', 1),
        5
        );

        return $this->render('pages/bien/index.html.twig', [
            'biens' => $biens,
            'form' => $form->createView()
        ]);
 }

     #[IsGranted('ROLE_USER')]
    #[Route('/bien/{id}', name: 'bien.show')]
    public function show(Bien  $bien): Response
    {

        return $this->render('pages/bien/show.html.twig', [
            'bien' => $bien
        ]);
    }
      




}
