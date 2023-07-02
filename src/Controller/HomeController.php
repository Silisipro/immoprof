<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Standing;
use App\Entity\User;
use App\Form\VdLogeType;
use App\Form\ContactType;
use App\Form\AskLogeType;
use App\Form\EsLoyerType;
use App\Form\ProgrammerVisiteType;
use App\Form\UserType;
use App\Form\CfLogeType;
use App\Repository\BienRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\EmailSmsServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private EmailSmsServices $emailSmsServices,
        private FileUploader $fileUploader,
    )
    {
    }
    
    #[Route('/', name: 'app_home')]
    public function index(BienRepository $bienRepository, Request $request): Response
    {
        $biens = $bienRepository->findLast();
        $formAsk = $this->createForm(AskLogeType::class);
        $formAsk->handleRequest($request);
        if ($formAsk->isSubmitted() && $formAsk->isValid()) {
            $data = [
                'nomPrenom' => $formAsk->get('nomPrenom')->getData(),
                'telephone' => $formAsk->get('telephone')->getData(),
                'emailForm' => $formAsk->get('email')->getData(),
                'zone' => $formAsk->get('zone')->getData(),
                'typeLogement' => $formAsk->get('typeLogement')->getData(),
                'standing' => $formAsk->get('standing')->getData(),
                'loyer' => (int) $formAsk->get('loyer')->getData(),
                'detail' => $formAsk->get('detail')->getData(),
            ];
         
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $formEsLoyer = $this->createForm(EsLoyerType::class);
        $formEsLoyer->handleRequest($request);
        if ($formEsLoyer->isSubmitted() && $formEsLoyer->isValid()) {
            $data = [
                'nomPrenom' => $formEsLoyer->get('nomPrenom')->getData(),
                'telephone' => $formEsLoyer->get('telephone')->getData(),
                'emailForm' => $formEsLoyer->get('email')->getData(),
                'zone' => $formEsLoyer->get('zone')->getData(),
                'typeLogement' => $formEsLoyer->get('typeLogement')->getData(),
                'standing' => $formEsLoyer->get('standing')->getData(),
                'detail' => $formEsLoyer->get('detail')->getData(),
                'files' => $formEsLoyer->get('files')->getData(),
            ];
            $tabFichiers = [];
           foreach ($data['files'] as $file) {
               if ($file instanceof UploadedFile) {
                   $tabFichiers[] = $this->fileUploader->saveFile(
                       $file,
                       false,
                       null,
                       null,
                       null,
                       false,
                        true
                    );
               }
            }
           
            // Suppression des fichiers à envoyer par mail du dossier default
            foreach ($tabFichiers as $file) {
                unlink($file);
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }


        $formCfBien = $this->createForm(CfLogeType::class);
        $formCfBien->handleRequest($request);
        if ($formCfBien->isSubmitted() && $formCfBien->isValid()) {
            $data = [
                'nomPrenom' => $formCfBien->get('nomPrenom')->getData(),
                'telephone' => $formCfBien->get('telephone')->getData(),
                'emailForm' => $formCfBien->get('email')->getData(),
                'zone' => $formCfBien->get('zone')->getData(),
                'typeLogement' => $formCfBien->get('typeLogement')->getData(),
                'standing' => $formCfBien->get('standing')->getData(),
                'detail' => $formCfBien->get('detail')->getData(),
                'files' => $formCfBien->get('files')->getData(),
                'dateHeureRdv' => $formCfBien->get('dateHeureRdv')->getData(),
                'typeRdv' => $formCfBien->get('typeRdv')->getData(),
            ];
            $tabFichiers = [];
            foreach ($data['files'] as $file) {
                if ($file instanceof UploadedFile) {
                    $tabFichiers[] = $this->fileUploader->saveFile(
                        $file,
                        false,
                        null,
                        null,
                        null,
                        false,
                        true
                    );
                }
            }
            $this->emailSmsServices->sendEmail(
                ['contact@immoprof.com', 'silisipro@gmail.com'],
                'email_templates/confier_mon_bien.html.twig',
                "Mise à disposition de mon bien à Immoprof",
                $data['emailForm'],
                $data['nomPrenom'],
                $data,
                $tabFichiers
            );
            // Suppression des fichiers à envoyer par mail du dossier default
            foreach ($tabFichiers as $file) {
                unlink($file);
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $formVdBien = $this->createForm(VdLogeType::class);
        $formVdBien->handleRequest($request);
        if ($formVdBien->isSubmitted() && $formVdBien->isValid()) {
            $data = [
                'nomPrenom' => $formVdBien->get('nomPrenom')->getData(),
                'telephone' => $formVdBien->get('telephone')->getData(),
                'emailForm' => $formVdBien->get('email')->getData(),
                'zone' => $formVdBien->get('zone')->getData(),
                'typeBien' => $formVdBien->get('typeBien')->getData(),
                'standing' => $formVdBien->get('standing')->getData(),
                'estimation' => (int) $formVdBien->get('estimation')->getData(),
                'detail' => $formVdBien->get('detail')->getData(),
                'dateHeureRdv' => $formVdBien->get('dateHeureRdv')->getData(),
                'typeRdv' => $formVdBien->get('typeRdv')->getData(),
                'files' => $formVdBien->get('files')->getData(),
            ];
            $tabFichiers = [];
            foreach ($data['files'] as $file) {
                if ($file instanceof UploadedFile) {
                    $tabFichiers[] = $this->fileUploader->saveFile(
                        $file,
                        false,
                        null,
                        null,
                        null,
                        false,
                        true
                    );
                }
            }
            $this->emailSmsServices->sendEmail(
                ['contact@e-immoprof.com', 'silisipro@gmail.com'],
                'email_templates/vendre_mon_bien.html.twig',
                "Vente de mon bien immobilier",
                $data['emailForm'],
                $data['nomPrenom'],
                $data,
                $tabFichiers
            );
            // Suppression des fichiers à envoyer par mail du dossier default
            foreach ($tabFichiers as $file) {
                unlink($file);
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/home.html.twig', [
            'biens' => $biens,
            'formAsk' => $formAsk->createView(),
            'formEsLoyer' => $formEsLoyer->createView(),
            'formVdBien' => $formVdBien->createView(),
            'formCfBien' => $formCfBien->createView()

        ]);

    }

    #[ROUTE ('/marque', name:'app_favori_marque')]
    public function favorieMarque(BienRepository $bienRepository) : Response

    {
        $favavisMarq = $bienRepository->findBy(
            [
                'sold'=>true,
                'deleted'=> false,               
            ],
            [
                'createdAt'=> 'ASC'
            ]
        );

        return $this->render('bien_favori\favori_marquee.html.twig',[
            'favorisMarq'=> $favavisMarq
        ]);
    }




    #[Route('/gestion_locative', name: 'app_gestion_locative', methods:['GET','POST'])]
    public function gestionLocative(Request $request ): Response
    {
        $formCfLoge = $this->createForm(CfLogeType::class);
        $formCfLoge->handleRequest($request);
        
        if ($formCfLoge->isSubmitted() && $formCfLoge->isValid()) { 
            $data =[
                'nomPrenom' => $formCfLoge->get('nomPrenom')->getData(),
                'telephone' => $formCfLoge->get('telephone')->getData(),
                'emailForm' => $formCfLoge->get('email')->getData(),
                'zone' => $formCfLoge->get('zone')->getData(),
                'typeLogement' => $formCfLoge->get('typeLogement')->getData(),
                'standing' => $formCfLoge->get('standing')->getData(),
                'detail' => $formCfLoge->get('detail')->getData(),
                'files' => $formCfLoge->get('files')->getData(),
                'dateHeureRdv' => $formCfLoge->get('dateHeureRdv')->getData(),
                'typeRdv' => $formCfLoge->get('typeRdv')->getData(),
            ];
        
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        };

        return $this->render('pages/gestion_locative/index.html.twig',[ 
            'formCfLoge' => $formCfLoge->createView()

        ]);
    }
  









}
