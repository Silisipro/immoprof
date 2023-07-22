<?php

namespace App\Controller;

use App\Entity\TypeBien;
use App\Entity\BienRecherche;
use App\Repository\BienRepository;
use App\Form\RetrouverBienLouerType;
use App\Form\RetrouverBienVendreType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheBienController extends AbstractController
{
    public function __construct(private BienRepository $bienRepository)
    {
    }
    #[Route('/recherche/{id}', name: 'app_bien_recherche')]
    public function index(TypeBien $typeBien, Request $request, PaginatorInterface $paginator, BienRepository $bienRepository): Response
    {
        $listeBienQuery = $this->bienRepository->recupererBiensParTypeBien($typeBien);
        $pagination = $paginator->paginate(
            $listeBienQuery,
            $request->query->getInt('page', 1),
            12
        );
        $formLouer = $this->createForm(RetrouverBienLouerType::class);
        $formLouer->handleRequest($request);
        if ($formLouer->isSubmitted() && $formLouer->isValid()) {
            $data = [
                'lieu' => $formLouer->get('lieu')->getData(),
                'typeBien' => $formLouer->get('typeBien')->getData(),
                'standing' => $formLouer->get('standing')->getData(),
                'maxPrice' => $formLouer->get('maxPrice')->getData(),
            ];
            $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_louer', $data);
            $pagination = $paginator->paginate(
                $listeBienQuery,
                $request->query->getInt('page', 1),
                12
            );
            
            return $this->render('pages/bien/louer.html.twig', [
                'pagination' => $pagination,
                'typeBien' => null,
                'form' => $formLouer->createView(),
            ]);
        }
        $formVendre = $this->createForm(RetrouverBienVendreType::class);
        $formVendre->handleRequest($request);
        if ($formVendre->isSubmitted() && $formVendre->isValid()) {
            $data = [
                'lieu' => $formVendre->get('lieu')->getData(),
                'typeBien' => $formVendre->get('typeBien')->getData(),
                'standing' => $formVendre->get('standing')->getData(),
                'maxPrice' => $formVendre->get('maxPrice')->getData(),
            ];
            $listeBienQuery = $bienRepository->recupererBiensParCategorie('a_vendre', $data);
            $pagination = $paginator->paginate(
                $listeBienQuery,
                $request->query->getInt('page', 1),
                12
            );
            return $this->render('pages/bien/vendre.html.twig', [
                'pagination' => $pagination,
                'typeBien' => null,
                'form' => $formVendre->createView(),
            ]);
        }
        if ($typeBien->getCategorie() == 'a_louer') {
            return $this->render('pages/bien/louer.html.twig', [
                'pagination' => $pagination,
                'typeBien' => $typeBien,
                'form' => $formLouer->createView(),
            ]);
        } else {
            return $this->render('pages/bien/vendre.html.twig', [
                'pagination' => $pagination,
                'typeBien' => $typeBien,
                'form' => $formVendre->createView(),
            ]);
        }
    }

    #[Route('/bien/a/louer', name: 'app_rechercher')]
    public function rechercher(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
     {

       $rechercher= new BienRecherche();
       $form = $this->createForm(RetrouverBienLouerType::class, $rechercher);
       $form->handleRequest($request);

       $biens= $bienRepository->rechercherBien( $rechercher);
       $pagination = $paginator->paginate(
        $biens,
        $request->query->getInt('page', 1),
        12
           );
        return $this->render('pages/bien/louer.html.twig', [
            'biens' => $biens,
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);

 }


 #[Route('/bien/a/vendre', name: 'app_rechercher_v')]
 public function rechercherBienVendre(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
  {

    $rechercherv= new BienRecherche();
    $form = $this->createForm(RetrouverBienVendreType::class, $rechercherv);
    $form->handleRequest($request);

    $biens= $bienRepository->rechercherBienv($rechercherv);
    $pagination = $paginator->paginate(
     $biens,
     $request->query->getInt('page', 1),
     12
        );
     return $this->render('pages/bien/vendre.html.twig', [
         'biens' => $biens,
         'pagination' => $pagination,
         'form' => $form->createView()
     ]);

}



}