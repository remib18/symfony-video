<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function search(string $query): array
    {
        $response = $this->httpClient->request('GET', 'https://www.omdbapi.com/', [
            'query' => [
                'apikey' => '293342ff',
                's' => $query,
            ],
        ]);

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getSeriesDetails(string $id): array
    {
        $response = $this->httpClient->request('GET', 'https://www.omdbapi.com/', [
            'query' => [
                'apikey' => '293342ff',
                'i' => $id,
            ],
        ]);

        return $response->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getSeasonEpisodes(string $id, int $season): array
    {
        $response = $this->httpClient->request('GET', 'https://www.omdbapi.com/', [
            'query' => [
                'apikey' => '293342ff',
                'i' => $id,
                'Season' => $season,
            ],
        ]);

        return $response->toArray();
    }
}