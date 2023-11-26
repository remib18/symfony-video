<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ImDBEntry;
use App\Entity\WebsiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupération du contenu Markdown dans la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->findOneBy([]);

        if ($websiteSettings === null) {
            throw new NotFoundHttpException('La configuration du site n\'a pas été trouvée.');
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

    #[Route('/search', name: 'search')]
    public function search(Request $request, HttpClientInterface $httpClient, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');

        // Recherche dans la base de données
        $repository = $entityManager->getRepository(ImDBEntry::class);
        $entry = $repository->findOneBy(['imDB_title' => $query]);

        if ($entry === null) {
            // Si l'entrée n'est pas trouvée dans la base de données, on fait une requête à l'API
            $response = $httpClient->request('GET', 'https://www.omdbapi.com/', [
                'query' => [
                    'apikey' => '293342ff',
                    't' => $query,
                ],
            ]);

            $data = $response->toArray();

            // Créez une nouvelle entrée avec les données de l'API
            $entry = new ImDBEntry();
            $entry->setImDBId($data['imdbID']);
            $entry->setImDBTitle($data['Title']);
            $entry->setImDBImageUrl($data['Poster']);
            $entry->setIsSerie($data['Type'] === 'series');

            // Ajout des catégories, si elles n'existent pas déjà
            $genres = explode(', ', $data['Genre']);
            foreach ($genres as $genreName) {
                $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => $genreName]);
                if ($category === null) {

                    $category = new Category();
                    $category->setName($genreName);
                    $category->setSlug(strtolower(str_replace(' ', '-', $genreName)));
                    $entityManager->persist($category);
                }
                $entry->addCategoryId($category);
            }

            $entityManager->persist($entry);
            $entityManager->flush();
        }

        return $this->render('home/search.html.twig', [
            'entry' => $entry,
        ]);
    }
}