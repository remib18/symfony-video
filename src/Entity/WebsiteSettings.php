<?php

namespace App\Entity;

use App\Repository\WebsiteSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteSettingsRepository::class)]
class WebsiteSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?HomePages $active_homepage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveHomepage(): ?HomePages
    {
        return $this->active_homepage;
    }

    public function setActiveHomepage(?HomePages $active_homepage): static
    {
        $this->active_homepage = $active_homepage;

        return $this;
    }
}
