<?php

namespace App\Entity;

use App\Repository\WebsiteSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteSettingsRepository::class)]

//Rajout de la contrainte d'unicité
#[ORM\Table(uniqueConstraints: [
    new ORM\UniqueConstraint(name: "singleton_guard", columns: ["singleton_guard"])
])]

class WebsiteSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    public int $singleton_guard = 1;

    #[ORM\OneToOne(inversedBy: "websiteSettings", targetEntity: HomePages::class)]
    private $active_homepage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveHomepage(): ?HomePages
    {
        return $this->active_homepage;
    }

    public function setActiveHomepage(?HomePages $activeHomepage): self
    {
        $this->active_homepage = $activeHomepage;

        return $this;
    }
}