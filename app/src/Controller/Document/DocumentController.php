<?php

namespace App\Controller\Document;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/documents')]
class DocumentController extends AbstractController
{
    #[Route('', name: 'document_overview')]
    public function index(DocumentRepository $documentRepository): Response
    {
        $documents = $documentRepository->findAll();

        return $this->render('admin/document/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/view/{documentId}', name: 'document_view')]
    public function viewDocument(
        string $documentId,
        DocumentRepository $documentRepository
    ): Response
    {
        $document = $documentRepository->find($documentId);

        if (!$document) {
            throw $this->createNotFoundException('Document not found');
        }

        return $this->render('admin/document/view.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/create', name: 'document_create')]
    public function createDocument(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $document = new Document();
        $document->setIsReleased(false); // Default: nicht veröffentlicht

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', 'Document wurde erfolgreich erstellt!');

            return $this->redirectToRoute('document_overview');
        }

        return $this->render('admin/document/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{documentId}', name: 'document_edit')]
    public function editDocument(
        string $documentId,
        Request $request,
        DocumentRepository $documentRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $document = $documentRepository->find($documentId);

        if (!$document) {
            throw $this->createNotFoundException('Document not found');
        }

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Document wurde erfolgreich aktualisiert!');
            return $this->redirectToRoute('document_overview');
        }

        return $this->render('admin/document/edit.html.twig', [
            'form' => $form,
            'document' => $document,
        ]);
    }

    #[Route('/delete/{documentId}', name: 'document_delete', methods: ['POST'])]
    public function deleteDocument(
        string $documentId,
        Request $request,
        DocumentRepository $documentRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $document = $documentRepository->find($documentId);

        if (!$document) {
            throw $this->createNotFoundException('Document not found');
        }

        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();

            $this->addFlash('success', 'Document wurde erfolgreich gelöscht!');
        } else {
            $this->addFlash('error', 'Ungültiges CSRF-Token. Document wurde nicht gelöscht.');
        }

        return $this->redirectToRoute('document_overview');
    }

}
