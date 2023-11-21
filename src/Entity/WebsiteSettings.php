<?php

namespace App\Entity;

use App\Repository\WebsiteSettingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: HomePages::class)]
    private Collection $active_homepages;

    public function __construct()
    {
        $this->active_homepages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, HomePages>
     */
    public function getActiveHomepages(): Collection
    {
        return $this->active_homepages;
    }

    public function addActiveHomepage(HomePages $activeHomepage): static
    {
        if (!$this->active_homepages->contains($activeHomepage)) {
            $this->active_homepages->add($activeHomepage);
        }

        return $this;
    }

    public function removeActiveHomepage(HomePages $activeHomepage): static
    {
        $this->active_homepages->removeElement($activeHomepage);

        return $this;
    }
}
