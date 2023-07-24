<?php

namespace App\Service;

use App\Entity\Files;
use App\Repository\FilesRepository;
use App\Service\GeneraleServices;
use App\Service\RandomStringGeneratorServices;
use App\Utils\Constants\AppConstants;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private SluggerInterface $slugger;
    private EntityManagerInterface $entityManager;
    private FilesRepository $filesRepository;
    private \App\Service\GeneraleServices $generaleServices;
    private \App\Service\RandomStringGeneratorServices $randomStringGeneratorServices;

    public function __construct(
        $targetDirectory,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        FilesRepository $filesRepository,
        GeneraleServices $generaleServices,
        RandomStringGeneratorServices $randomStringGeneratorServices
    )
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->filesRepository = $filesRepository;
        $this->generaleServices = $generaleServices;
        $this->randomStringGeneratorServices = $randomStringGeneratorServices;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function saveFile(
        $file,
        bool $temp = false,
        ?string $entityClass = null,
        ?string $existFileCode = null,
        ?string $reference = null,
        bool $returnFileObj = false,
        bool $sendByEmail = false,
    )
    {
        // Fichiers à envoyer par mail
        if ($sendByEmail) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            // Get folder location based on entity
            $location = $this->getFileFolderDependOnEntity(null);

            try {
                $file->move($location, $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            return dirname(__FILE__, 3) . '/public/' . $location . $fileName;
        }

        // Remove file from DB & FileFolder if exist
        if ($existFileCode !== null) {
            $existFiles = $this->filesRepository->findBy([
                'referenceCode' => $existFileCode,
            ]);

            foreach ($existFiles as $existFile) {
                try {
                    unlink(dirname(__FILE__, 3) . '/public/' . $existFile->getLocation() . $existFile->getFilename());
                } catch (\Exception) {
                }
                $this->filesRepository->remove($existFile);
            }
        }

        // Create and save new file
        if ($reference === null) {
            $reference = $this->randomStringGeneratorServices->random_alphanumeric_custom_length(7);
        }

        $extension = $file->getClientOriginalExtension();
        $fileNewName = md5(uniqid()) . '.' . $extension;
        $fileSize = $file->getSize();

        // Get folder location based on entity
        $location = $this->getFileFolderDependOnEntity(
            ($entityClass === null || trim($entityClass) === "") ? "aucune_entite" : $this->generaleServices->getTableName($entityClass)
        );
        $file->move($location, $fileNewName);
        $file = new Files();
        $file->setFilename($fileNewName);
        $file->setTemp($temp);
        $file->setSize($fileSize);
        $file->setLocation($location);
        $file->setType($extension);
        $file->setReferenceCode($reference);
        
        $this->entityManager->persist($file);

        if ($returnFileObj) {
            return $file;
        }
        return $reference;
    }

    private function getFileFolderDependOnEntity($entityClass)
    {
        switch ($entityClass) {
            case'bien':
                return AppConstants::BIEN_FOLDER;           
            default:
                return AppConstants::DEFAULT_FOLDER;
        }
    }

    public function getFilesByFileCode(
        string $filesCode,
        string $returnSingleFile = '0'
    )
    {
        if ($returnSingleFile === '1') {
            $singleFile = $this->filesRepository->findOneBy([
                'referenceCode' => $filesCode,
            ]);
            if ($singleFile) {
                return $singleFile->getLocation() . $singleFile->getFilename();
            }
            return $filesCode;
        }

        return $this->filesRepository->findBy([
            'referenceCode' => $filesCode,
        ]);
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
