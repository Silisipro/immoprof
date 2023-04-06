<?php

namespace App\Controller;

use App\Entity\Bien;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class BienController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/bien/{id}', name: 'bien.show')]
    public function show(Bien  $bien): Response
    {

        return $this->render('pages/bien/show.html.twig', [
            'bien' => $bien
        ]);
    }





}
