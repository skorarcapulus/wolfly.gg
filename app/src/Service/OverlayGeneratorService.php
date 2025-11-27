<?php

namespace App\Service;

use App\Entity\Overlay;
use App\Entity\Document;
use Psr\Log\LoggerInterface;

class OverlayGeneratorService
{
    public function __construct(
        private readonly string $generatedTemplatesPath,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function generateOverlay(Overlay $overlay): void
    {
        $this->logger->info('Generating overlay files', [
            'overlay_id' => $overlay->getId(),
            'overlay_title' => $overlay->getTitle()
        ]);

        $documents = $overlay->getDocuments();
        $this->logger->debug('Found documents for overlay', ['count' => count($documents)]);

        /**
         * @var Document $document
         */
        foreach ($documents as $document) {
            if ($document->isReleased() && $document->getSource() !== null && $document->getType() !== null) {
                $folderPath = $this->getFolderPath($overlay, $document);
                $sourceCode = $document->getSource();
                $filePath = sprintf('%s/%s.%s', $folderPath, $document->getId(), $document->getType()->value);

                $this->logger->debug('Writing document file', [
                    'document_id' => $document->getId(),
                    'document_title' => $document->getTitle(),
                    'file_path' => $filePath
                ]);

                $result = file_put_contents($filePath, $sourceCode);

                if ($result === false) {
                    $this->logger->error('Failed to write file', ['file_path' => $filePath]);
                    throw new \RuntimeException(sprintf('Failed to write file: %s', $filePath));
                }

                $this->logger->info('Successfully wrote document file', [
                    'document_title' => $document->getTitle(),
                    'file_path' => $filePath
                ]);
            } else {
                // If the document is not released, remove the generated file if it exists
                $folderPath = $this->getFolderPath($overlay, $document);
                $filePath = sprintf('%s/%s.%s', $folderPath, $document->getId(), $document->getType()->value);

                if (file_exists($filePath)) {
                    $this->logger->info('Deleting unreleased document file', [
                        'document_title' => $document->getTitle(),
                        'file_path' => $filePath
                    ]);

                    if (!unlink($filePath)) {
                        $this->logger->error('Failed to delete file', ['file_path' => $filePath]);
                        throw new \RuntimeException(sprintf('Failed to delete file: %s', $filePath));
                    }
                }
            }
        }
    }

    public function getFolderPath(Overlay $overlay, Document $document): string
    {
        $folderPath = sprintf('%s/%s/%s', $this->generatedTemplatesPath, $overlay->getId(), $document->getType()->value);
        if (!is_dir($folderPath)) {
            $this->logger->debug('Creating directory', ['path' => $folderPath]);

            if(!mkdir($folderPath, 0755, true)) {
                $this->logger->error('Failed to create directory', ['path' => $folderPath]);
                throw new \RuntimeException(sprintf('Failed to create directory: %s', $folderPath));
            }
        }

        return $folderPath;
    }

    public function deleteDocumentFile(Document $document): void
    {
        $overlay = $document->getOverlay();
        if ($overlay) {
            $folderPath = $this->getFolderPath($overlay, $document);
            $filePath = sprintf('%s/%s.%s', $folderPath, $document->getId(), $document->getType()->value);

            if (file_exists($filePath)) {
                $this->logger->info('Deleting document file', [
                    'document_title' => $document->getTitle(),
                    'file_path' => $filePath
                ]);

                if (!unlink($filePath)) {
                    $this->logger->error('Failed to delete file', ['file_path' => $filePath]);
                    throw new \RuntimeException(sprintf('Failed to delete file: %s', $filePath));
                }
            }
        }
    }
}