<?php

namespace App\EventSubscriber;

use App\Entity\Document;
use App\Service\OverlayGeneratorService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postFlush)]
class DocumentOverlaySubscriber
{
    private array $documentsToProcess = [];

    public function __construct(
        private readonly OverlayGeneratorService $overlayGeneratorService
    ) {
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->addDocumentToQueue($args->getObject());
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->addDocumentToQueue($args->getObject());
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        // Process all documents after flush (when all relations are saved)
        foreach ($this->documentsToProcess as $document) {
            $this->generateOverlays($document);
        }

        // Clear the queue
        $this->documentsToProcess = [];
    }

    private function addDocumentToQueue(object $entity): void
    {
        if ($entity instanceof Document) {
            $this->documentsToProcess[] = $entity;
        }
    }

    private function generateOverlays(Document $document): void
    {
        $overlay = $document->getOverlay();
        if ($overlay !== null) {
            $this->overlayGeneratorService->generateOverlay($overlay);
        }
    }
}