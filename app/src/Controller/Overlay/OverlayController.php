<?php

namespace App\Controller\Overlay;

use App\Repository\OverlayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/overlays')]
class OverlayController extends AbstractController
{
    #[Route('', name: 'overlay_overview')]
    public function index(OverlayRepository $overlayRepository): Response
    {
        $overlays = $overlayRepository->findAll();

        return $this->render('overlay/index.html.twig', [
            'overlays' => $overlays,
        ]);
    }

    #[Route('/view/{overlayId}', name: 'overlay_view')]
    public function viewOverlay(
        string $overlayId,
        OverlayRepository $overlayRepository
    ): Response
    {
        $overlay = $overlayRepository->find($overlayId);

        if (!$overlay) {
            throw $this->createNotFoundException('Overlay not found');
        }

        return $this->render('overlay/view.html.twig', [
            'overlay' => $overlay,
        ]);
    }
}
