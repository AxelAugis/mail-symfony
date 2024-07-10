<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class PdfController extends AbstractController
{
    private $knpSnappyPdf;
    private $entityManager;

    public function __construct(Pdf $knpSnappyPdf, EntityManagerInterface $entityManager)
    {
        $this->knpSnappyPdf = $knpSnappyPdf;
        $this->entityManager = $entityManager;
    }

    #[Route('/generate-pdf-user/{id}', name: 'app_generate_pdf_user')]
    public function generatePdf($id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $html = $this->renderView('pdf/user.html.twig', [
            'user' => $user
        ]);

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
        $pdfFilePath = $publicDirectory . '/pdf/user_' . $user->getId() . '.pdf';

        $this->knpSnappyPdf->generateFromHtml($html, $pdfFilePath);

        return new JsonResponse([
            'message' => 'PDF généré avec succès',
            'file_path' => '/pdf/user_' . $user->getId() . '.pdf'
        ]);
    }
}
