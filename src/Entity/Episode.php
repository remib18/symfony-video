<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $serie_imDB_id = null;

    #[ORM\Column(length: 255)]
    private ?string $episode_imDB_id = null;

    #[ORM\Column]
    private ?int $season = null;

    #[ORM\Column(length: 255)]
    private ?string $episode_imDB_title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerieImDBId(): ?string
    {
        return $this->serie_imDB_id;
    }

    public function setSerieImDBId(string $serie_imDB_id): static
    {
        $this->serie_imDB_id = $serie_imDB_id;

        return $this;
    }

    public function getEpisodeImDBId(): ?string
    {
        return $this->episode_imDB_id;
    }

    public function setEpisodeImDBId(string $episode_imDB_id): static
    {
        $this->episode_imDB_id = $episode_imDB_id;

        return $this;
    }

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(int $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getEpisodeImDBTitle(): ?string
    {
        return $this->episode_imDB_title;
    }

    public function setEpisodeImDBTitle(string $episode_imDB_title): static
    {
        $this->episode_imDB_title = $episode_imDB_title;

        return $this;
    }
}
