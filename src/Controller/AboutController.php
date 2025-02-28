<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/a_propos_de_nous', name: 'app.about')]
    public function index(): Response
    {
        return $this->render('pages/about/index.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
}
