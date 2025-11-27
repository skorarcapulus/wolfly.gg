<?php

namespace App\EventListener;

use App\Entity\Document;
use App\Service\OverlayGeneratorService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, entity: Document::class)]
#[AsEntityListener(event: Events::postUpdate, entity: Document::class)]
#[AsEntityListener(event: Events::postRemove, entity: Document::class)]
class OverlayListener
{
    private OverlayGeneratorService $overlayGeneratorService;

    public function __construct(OverlayGeneratorService $overlayGeneratorService)
    {
        $this->overlayGeneratorService = $overlayGeneratorService;
    }

    public function postPersist(Document $document): void
    {
        $this->generateOverlays($document);
    }

    public function postUpdate(Document $document): void
    {
        $this->generateOverlays($document);
    }

    public function postRemove(Document $document): void
    {
        $this->overlayGeneratorService->deleteDocumentFile($document);
    }

    private function generateOverlays(Document $document): void
    {
        $overlay = $document->getOverlay();
        if ($overlay) {
            $this->overlayGeneratorService->generateOverlay($overlay);
        }
    }
}