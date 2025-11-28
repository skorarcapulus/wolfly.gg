<?php

namespace App\Controller\Overlay;

use App\Enum\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\OverlayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/output')]
class OutputController extends AbstractController
{
    #[Route('/{overlayId}', name: 'output_overview')]
    public function index(
        string $overlayId,
        OverlayRepository $overlayRepository
    ): Response
    {
        $overlay = $overlayRepository->find($overlayId);

        if (!$overlay) {
            throw $this->createNotFoundException('Overlay not found');
        }

        return $this->render('admin/output/index.html.twig', [
            'overlay' => $overlay,
            'documentTypeHtml' => DocumentType::HTML,
        ]);
    }

    #[Route('/view/{overlayId}/{documentId}', name: 'output_view')]
    public function viewOutput(
        string $overlayId,
        string $documentId,
        OverlayRepository $overlayRepository,
        DocumentRepository $documentRepository
    ): Response
    {
        $overlay = $overlayRepository->find($overlayId);
        $document = $documentRepository->find($documentId);

        if (!$overlay) {
            throw $this->createNotFoundException('Overlay not found');
        }

        if (!$document) {
            throw $this->createNotFoundException('Document not found');
        }

        // Check if document belongs to the overlay
        if ($document->getOverlay() && $document->getOverlay()->getId() !== $overlay->getId()) {
            throw $this->createNotFoundException('Document does not belong to this overlay');
        }

        return $this->render('admin/output/view.html.twig', [
            'overlay' => $overlay,
            'document' => $document,
            'documentTypeHtml' => DocumentType::HTML,
        ]);
    }
}


