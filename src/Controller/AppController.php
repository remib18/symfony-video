<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\ImDBEntry;
use App\Repository\CategoryRepository;
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
    public function app(CategoryRepository $categoryRepository): Response
    {
        $genres = $categoryRepository->findAll();

        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'genres' => $genres,
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
    public function search(Request $request, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository,SessionInterface $session): Response
    {
        $query = $request->query->get('query');
        $data = $omdbApiService->search($query);
        $genres = $categoryRepository->findAll();
        $entries = [];
        if (isset($data['Search'])) {
            foreach ($data['Search'] as $item) {
                // Recherche dans la base de données
                $repository = $entityManager->getRepository(ImDBEntry::class);
                $entry = $repository->findOneBy(['imDB_id' => $item['imdbID']]);

                if ($entry === null) {
                    $entry = new ImDBEntry();
                    $entry->setImDBId($item['imdbID']);
                    $entry->setImDBTitle($item['Title']);
                    $entry->setImDBImageUrl($item['Poster']);
                    $entry->setIsSerie($item['Type'] === 'series');

                    $details = $omdbApiService->getSeriesDetails($item['imdbID']);
                    if (isset($details['Genre'])) {
                        $entryGenres = explode(', ', $details['Genre']);
                        foreach ($entryGenres as $genreName) {
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

            $session->set('search_results', $entries);
            $selectedGenres = $request->query->all('genres');
            $isMovie = $request->query->get('movies') !== null;
            $isSeries = $request->query->get('series') !== null;
            if ($selectedGenres || $isMovie || $isSeries) {
                $entries = $this->filterResults($entries, $selectedGenres, $isMovie, $isSeries);
            }

        }

        return $this->render('app/index.html.twig', [
            'entries' => $entries,
            'genres' => $genres,

        ]);
    }

    #[Route('/series/{id}/episodes/{season}', name: 'app_series_episodes', defaults: ["season" => 1])]
    public function episodes(string $id, int $season, OmdbApiService $omdbApiService, EntityManagerInterface $entityManager): Response
    {
        $episodes = $entityManager->getRepository(Episode::class)->findBy(['serie_imDB_id' => $id, 'season' => $season]);

        if (empty($episodes)) {
            $data = $omdbApiService->getSeasonEpisodes($id, $season);

            if (isset($data['Episodes'])) {
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



    private function filterResults($entries, $selectedGenres, $isMovie, $isSeries) {
        return array_filter($entries, function ($entry) use ($selectedGenres, $isMovie, $isSeries) {
            $entryGenres = $entry->getGenresNames();

            $genreMatch = empty($selectedGenres) || !empty(array_intersect($entryGenres, $selectedGenres));
            $typeMatch = ($isMovie && !$entry->isIsSerie()) || ($isSeries && $entry->isIsSerie());

            return $genreMatch && $typeMatch;
        });
    }

}
