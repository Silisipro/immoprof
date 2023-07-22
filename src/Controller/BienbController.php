<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\BienRecherche;
use App\Form\RetrouverBienLouerType;
use App\Form\BienRechercheType;
use App\Form\RetrouverBienVendreType;
use App\Repository\BienRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BienbController extends AbstractController
{
    
    #[Route('/bien/disponible', name: 'bien.tout.index', methods:['GET'])]
    public function index(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
     {

       $recherche= new BienRecherche();
       $form = $this->createForm(BienRechercheType::class, $recherche);
       $form->handleRequest($request);

       $biens= $paginator->paginate(
        $bienRepository->findVisible($recherche),
        $request->query->getInt('page', 1)
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
      
    #[Route('/louer', name: 'app_louer')]
    public function louer(Request $request, BienRepository $bienRepository, PaginatorInterface $paginator): Response
    {
        $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_louer');
        $pagination = $paginator->paginate(
            $listeBienQuery,
            $request->query->getInt('page', 1),
            20
        );
        $form = $this->createForm(RetrouverBienLouerType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = [
                'lieu' => $form->get('lieu')->getData(),
                'type' => $form->get('type')->getData(),
                'standing' => $form->get('standing')->getData(),
                'maxPrice' => $form->get('maxPrice')->getData(),
            ];
            $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_louer', $data);
            $pagination = $paginator->paginate(
                $listeBienQuery,
                $request->query->getInt('page', 1),
                20
            );
            return $this->render('pages/bien/louer.html.twig', [
                'pagination' => $pagination,
                'typeBien' => null,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('pages/bien/louer.html.twig', [
            'pagination' => $pagination,
            'typeBien' => null,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vendre', name: 'app_vendre')]
    public function vendre(Request $request, BienRepository $bienRepository, PaginatorInterface $paginator): Response
    {
        $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_vendre');
        $pagination = $paginator->paginate(
            $listeBienQuery,
            $request->query->getInt('page', 1),
            4
        );
        $form = $this->createForm(RetrouverBienVendreType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = [
                'lieu' => $form->get('lieu')->getData(),
                'typeBien' => $form->get('typeBien')->getData(),
                'standing' => null,
                'maxPrice' => $form->get('maxPrice')->getData(),
            ];
            $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_vendre', $data);
            $pagination = $paginator->paginate(
                $listeBienQuery,
                $request->query->getInt('page', 1),
                4
            );
            return $this->render('pages/bien/vendre.html.twig', [
                'pagination' => $pagination,
                'typeBien' => null,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('pages/bien/vendre.html.twig', [
            'pagination' => $pagination,
            'typeBien' => null,
            'form' => $form->createView(),
        ]);
    }

}
