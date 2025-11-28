<?php

namespace App\Controller\Overlay;

use App\Entity\Overlay;
use App\Form\OverlayType;
use App\Repository\OverlayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/overlays')]
class OverlayController extends AbstractController
{
    #[Route('', name: 'overlay_overview')]
    public function index(OverlayRepository $overlayRepository): Response
    {
        $overlays = $overlayRepository->findAll();

        return $this->render('admin/overlay/index.html.twig', [
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

        return $this->render('admin/overlay/view.html.twig', [
            'overlay' => $overlay,
        ]);
    }

    #[Route('/create', name: 'overlay_create')]
    public function createOverlay(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $overlay = new Overlay();
        $overlay->setIsReleased(false); // Default: nicht veröffentlicht

        $form = $this->createForm(OverlayType::class, $overlay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($overlay);
            $entityManager->flush();

            $this->addFlash('success', 'Overlay wurde erfolgreich erstellt!');

            return $this->redirectToRoute('overlay_overview');
        }

        return $this->render('admin/overlay/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{overlayId}', name: 'overlay_edit')]
    public function editOverlay(
        string $overlayId,
        Request $request,
        OverlayRepository $overlayRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $overlay = $overlayRepository->find($overlayId);

        if (!$overlay) {
            throw $this->createNotFoundException('Overlay not found');
        }

        $form = $this->createForm(OverlayType::class, $overlay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Overlay wurde erfolgreich aktualisiert!');
            return $this->redirectToRoute('overlay_overview');
        }

        return $this->render('admin/overlay/edit.html.twig', [
            'form' => $form,
            'overlay' => $overlay,
        ]);
    }

    #[Route('/delete/{overlayId}', name: 'overlay_delete', methods: ['POST'])]
    public function deleteOverlay(
        string $overlayId,
        Request $request,
        OverlayRepository $overlayRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $overlay = $overlayRepository->find($overlayId);

        if (!$overlay) {
            throw $this->createNotFoundException('Overlay not found');
        }

        if ($this->isCsrfTokenValid('delete' . $overlay->getId(), $request->request->get('_token'))) {
            $entityManager->remove($overlay);
            $entityManager->flush();

            $this->addFlash('success', 'Overlay wurde erfolgreich gelöscht!');
        } else {
            $this->addFlash('error', 'Ungültiges CSRF-Token. Overlay wurde nicht gelöscht.');
        }

        return $this->redirectToRoute('overlay_overview');
    }

}
