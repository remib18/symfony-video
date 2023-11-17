<?php

namespace App\Entity;

use App\Repository\WebsiteSettingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteSettingsRepository::class)]
class WebsiteSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'websiteSettings', targetEntity: HomePages::class)]
    private Collection $active_homepage;

    public function __construct()
    {
        $this->active_homepage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, HomePages>
     */
    public function getActiveHomepage(): Collection
    {
        return $this->active_homepage;
    }

    public function addActiveHomepage(HomePages $activeHomepage): static
    {
        if (!$this->active_homepage->contains($activeHomepage)) {
            $this->active_homepage->add($activeHomepage);
            $activeHomepage->setWebsiteSettings($this);
        }

        return $this;
    }

    public function removeActiveHomepage(HomePages $activeHomepage): static
    {
        if ($this->active_homepage->removeElement($activeHomepage)) {
            // set the owning side to null (unless already changed)
            if ($activeHomepage->getWebsiteSettings() === $this) {
                $activeHomepage->setWebsiteSettings(null);
            }
        }

        return $this;
    }
}
