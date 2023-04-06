<?php

namespace App\Controller;

use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BienRepository $bienRepository): Response
    {
        $biens = $bienRepository->findLast();
        return $this->render('pages/home.html.twig', [
            'biens' => $biens,
        ]);
    }
}
