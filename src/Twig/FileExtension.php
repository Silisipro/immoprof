<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\FileUploader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExtension extends AbstractExtension
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getFilesByFileCode', [$this, 'getFilesByFileCode']),
        ];
    }

    public function getFilesByFileCode(string $filesCode, string $returnSingleFile)
    {
        return $this->fileUploader->getFilesByFileCode($filesCode, $returnSingleFile);
    }
}
