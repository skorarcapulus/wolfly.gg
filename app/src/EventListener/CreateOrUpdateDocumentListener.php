<?php

namespace App\EventListener;

use App\Entity\Document;
use App\Service\OverlayGeneratorService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postFlush, entity: Document::class)]
#[AsEntityListener(event: Events::postUpdate, entity: Document::class)]
class CreateOrUpdateDocumentListener
{
    private OverlayGeneratorService $overlayGeneratorService;

    public function __construct(OverlayGeneratorService $overlayGeneratorService)
    {
        $this->overlayGeneratorService = $overlayGeneratorService;
    }

    public function postFlush(Document $document): void
    {
        $this->generateOverlays($document);
    }

    public function postUpdate(Document $document): void
    {
        $this->generateOverlays($document);
    }

    private function generateOverlays(Document $document): void
    {
        $overlays = $document->getOverlays();
        foreach ($overlays as $overlay) {
            $projects = $overlay->getProjects();
            foreach ($projects as $project) {
                $this->overlayGeneratorService->generateOverlay($project, $overlay);
            }
        }
    }
}