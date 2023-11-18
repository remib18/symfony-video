<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\WebsiteSettings;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\CommonMark\CommonMarkConverter;
use Doctrine\ORM\EntityManagerInterface;

class AppController extends AbstractController
{

    #[Route(path: '/app', name: 'app_app')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérez le contenu Markdown de la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->find(1);

        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        $markdownContent = $websiteSettings->getActiveHomepage()->getMarkdown();

        // Convertissez le contenu Markdown en HTML
        $converter = new CommonMarkConverter();
        try {
            $htmlContent = $converter->convertToHtml($markdownContent);
        } catch (CommonMarkException $e) {
            $this->logger->error('Erreur lors de la conversion du Markdown en HTML : ' . $e->getMessage());
            $htmlContent = '<p>Une erreur s\'est produite lors de la conversion du contenu.</p>';

        }

        // Passez le contenu HTML à la vue
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'content' => $htmlContent,
        ]);
    }
}
