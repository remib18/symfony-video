<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\ImDBEntry;
use App\Service\OmdbApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function app(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');
        $data = $omdbApiService->search($query);

        $entries = [];
        if (isset($data['Search'])) {
            foreach ($data['Search'] as $item) {
                // Recherche dans la base de données
                $repository = $entityManager->getRepository(ImDBEntry::class);
                $entry = $repository->findOneBy(['imDB_id' => $item['imdbID']]);

                if ($entry === null) {
                    // Créez une nouvelle entrée avec les données de l'API
                    $entry = new ImDBEntry();
                    $entry->setImDBId($item['imdbID']);
                    $entry->setImDBTitle($item['Title']);
                    $entry->setImDBImageUrl($item['Poster']);
                    $entry->setIsSerie($item['Type'] === 'series');

                    // Récupération des détails du film ou de la série
                    $details = $omdbApiService->getSeriesDetails($item['imdbID']);

                    // Ajout des catégories, si elles n'existent pas déjà
                    if (isset($details['Genre'])) {
                        $genres = explode(', ', $details['Genre']);
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
                    }

                    $entityManager->persist($entry);
                    $entityManager->flush();
                }

                $entries[] = $entry;
            }
        }

        return $this->render('app/index.html.twig', [
            'entries' => $entries,
        ]);
    }

    #[Route('/series/{id}/episodes/{season}', name: 'app_series_episodes', defaults: ["season" => 1])]
    public function episodes(string $id, int $season, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager): Response
    {
        // Récupération des épisodes dans la base de données pour les afficher
        $episodes = $entityManager->getRepository(Episode::class)->findBy(['serie_imDB_id' => $id, 'season' => $season]);

        // Si aucun épisode n'est trouvé dans la base de données, faire une requête à l'API
        if (empty($episodes)) {
            $data = $omdbApiService->getSeasonEpisodes($id, $season);

            if (isset($data['Episodes'])) {
                // Parcours des épisodes et enregistrements dans la base de données
                foreach ($data['Episodes'] as $episodeData) {
                    $episode = new Episode();
                    $episode->setSerieImDBId($id);
                    $episode->setEpisodeImDBId($episodeData['imdbID']);
                    $episode->setSeason($season);
                    $episode->setEpisodeImDBTitle($episodeData['Title']);
                    $episode->setNoEpisode((int)$episodeData['Episode']);

                    $entityManager->persist($episode);
                }
            }

            $entityManager->flush();

            // Récupération des épisodes dans la base de données pour les afficher
            $episodes = $entityManager->getRepository(Episode::class)->findBy(['serie_imDB_id' => $id, 'season' => $season]);
        }

        // Récupération du nombre total de saisons
        $data = $omdbApiService->getSeriesDetails($id);
        $totalSeasons = $data['totalSeasons'];

        $entry = $entityManager->getRepository(ImDBEntry::class)->findOneBy(['imDB_id' => $id]);

        return $this->render('app/episodes.html.twig', [
            'episodes' => $episodes,
            'totalSeasons' => $totalSeasons,
            'currentSeason' => $season,
            'entry' => $entry,
        ]);
    }

}
