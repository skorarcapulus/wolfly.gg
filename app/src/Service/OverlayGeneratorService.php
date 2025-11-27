<?php

namespace App\Service;

use App\Entity\Overlay;
use App\Entity\Document;

class OverlayGeneratorService
{
    public function __construct(
        private readonly string $generatedTemplatesPath
    )
    {
    }

    public function generateOverlay(Overlay $overlay): void
    {
        $documents = $overlay->getDocuments();

        /**
         * @var Document $document
         */
        foreach ($documents as $document) {
            if ($document->isReleased()) {
                $folderPath = $this->getFolderPath($overlay, $document);
                $sourceCode = $document->getSource();
                $filePath = sprintf('%s/%s.%s', $folderPath, $document->getId(), $document->getType()->value);

                $result = file_put_contents($filePath, $sourceCode);

                if ($result === false) {
                    throw new \RuntimeException(sprintf('Failed to write file: %s', $filePath));
                }
            } else {
                // If the document is not released, remove the generated file if it exists
                $folderPath = $this->getFolderPath($overlay, $document);
                $filePath = sprintf('%s/%s.%s', $folderPath, $document->getId(), $document->getType()->value);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }

    public function getFolderPath(Overlay $overlay, Document $document): string
    {
        $folderPath = sprintf('%s/%s/%s', $this->generatedTemplatesPath, $overlay->getId(), $document->getType()->value);
        if (!is_dir($folderPath)) {
            if(!mkdir($folderPath, 0755, true)) {
                throw new \RuntimeException(sprintf('Failed to create directory: %s', $folderPath));
            }
        }

        return $folderPath; 
    }
}