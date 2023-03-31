<?php

namespace App\Controller;

use App\Entity\Bien;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    #[Route('/bien/{id}', name: 'bien.show')]
    public function show(Bien  $bien): Response
    {

        return $this->render('pages/bien/show.html.twig', [
            'bien' => $bien
        ]);
    }





}
