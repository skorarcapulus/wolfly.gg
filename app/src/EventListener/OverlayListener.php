<?php

namespace App\EventListener;

use App\Entity\Document;
use App\Service\OverlayGeneratorService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postPersist, entity: Document::class)]
#[AsEntityListener(event: Events::postUpdate, entity: Document::class)]
#[AsEntityListener(event: Events::postRemove, entity: Document::class)]
class OverlayListener
{
    public function __construct(
        private readonly OverlayGeneratorService $overlayGeneratorService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function postPersist(Document $document): void
    {
        $this->logger->info('Document postPersist event triggered', [
            'document_id' => $document->getId(),
            'document_title' => $document->getTitle()
        ]);
        $this->generateOverlays($document);
    }

    public function postUpdate(Document $document): void
    {
        $this->logger->info('Document postUpdate event triggered', [
            'document_id' => $document->getId(),
            'document_title' => $document->getTitle()
        ]);
        $this->generateOverlays($document);
    }

    public function postRemove(Document $document): void
    {
        $this->logger->info('Document postRemove event triggered', [
            'document_id' => $document->getId(),
            'document_title' => $document->getTitle()
        ]);
        $this->overlayGeneratorService->deleteDocumentFile($document);
    }

    private function generateOverlays(Document $document): void
    {
        $overlay = $document->getOverlay();
        if ($overlay) {
            $this->logger->debug('Document has overlay, triggering generation', [
                'overlay_id' => $overlay->getId(),
                'overlay_title' => $overlay->getTitle()
            ]);
            $this->overlayGeneratorService->generateOverlay($overlay);
        } else {
            $this->logger->debug('Document has no overlay, skipping generation');
        }
    }
}