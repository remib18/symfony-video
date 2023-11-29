<?php

namespace App\Controller;

use App\Entity\WebsiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use League\CommonMark\CommonMarkConverter;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération du contenu Markdown dans la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->findOneBy([]);

        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        $activeHomepage = $websiteSettings->getActiveHomepage();
        if ($activeHomepage !== null) {
            $markdownContent = $activeHomepage->getMarkdown();
        } else {
            $markdownContent = 'Aucun contenu n\'est disponible pour le moment.';
        }

        // Convertion du contenu Markdown en HTML
        $converter = new CommonMarkConverter();
        try {
            $htmlContent = $converter->convertToHtml($markdownContent);
        } catch (CommonMarkException) {
            $htmlContent = '<p>Une erreur s\'est produite lors de la conversion du contenu.</p>';
        }

        // On passe le contenu HTML à la vue
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'content' => $htmlContent,
        ]);
    }
}