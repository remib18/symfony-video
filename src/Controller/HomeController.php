<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\ImDBEntry;
use App\Entity\WebsiteSettings;
use App\Service\OmdbApiService;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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

        // Configuration du convertisseur Markdown
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        // Convertion du contenu Markdown en HTML
        $converter = new MarkdownConverter($environment);
        try {
            $htmlContent = $converter->convert($markdownContent);
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
    public function search(Request $request, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');

        // Recherche dans la base de données
        $repository = $entityManager->getRepository(ImDBEntry::class);
        $entry = $repository->findOneBy(['imDB_title' => $query]);

        if ($entry === null) {
            $data = $omdbApiService->search($query);

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

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[Route('/series/{id}/episodes/{season}', name: 'series_episodes', defaults: ["season" => 1])]
    public function episodes(string $id, int $season, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager): Response
    {
        // Récupération des épisodes dans la base de données pour les afficher
        $episodes = $entityManager->getRepository(Episode::class)->findBy(['serie_imDB_id' => $id, 'season' => $season]);

        // Si aucun épisode n'est trouvé dans la base de données, faire une requête à l'API
        if (empty($episodes)) {
            $data = $omdbApiService->getSeasonEpisodes($id, $season);

            // Parcours des épisodes et enregistrements dans la base de données
            foreach ($data['Episodes'] as $episodeData) {
                $episode = new Episode();
                $episode->setSerieImDBId($id);
                $episode->setEpisodeImDBId($episodeData['imdbID']);
                $episode->setSeason($season);
                $episode->setEpisodeImDBTitle($episodeData['Title']);
                $episode->setNoEpisode((int) $episodeData['Episode']);

                $entityManager->persist($episode);
            }

            $entityManager->flush();

            // Récupération des épisodes dans la base de données pour les afficher
            $episodes = $entityManager->getRepository(Episode::class)->findBy(['serie_imDB_id' => $id, 'season' => $season]);
        }

        // Récupération du nombre total de saisons
        $data = $omdbApiService->getSeriesDetails($id);
        $totalSeasons = $data['totalSeasons'];

        $entry = $entityManager->getRepository(ImDBEntry::class)->findOneBy(['imDB_id' => $id]);

        return $this->render('home/episodes.html.twig', [
            'episodes' => $episodes,
            'totalSeasons' => $totalSeasons,
            'currentSeason' => $season,
            'entry' => $entry,
        ]);
    }
}