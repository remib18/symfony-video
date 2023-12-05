<?php

namespace App\Entity;

use App\Repository\HomePagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HomePagesRepository::class)]
class HomePages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: 'text')]
    private ?string $markdown = null;

    #[ORM\OneToOne(mappedBy: "active_homepage", targetEntity: WebsiteSettings::class)]
    private ?WebsiteSettings $websiteSettings;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getMarkdown(): ?string
    {
        return $this->markdown;
    }

    public function setWebsiteSettings(?WebsiteSettings $websiteSettings): self
    {

        if ($websiteSettings === null && $this->websiteSettings !== null) {
            $this->websiteSettings->setActiveHomepage(null);
        }

        if ($websiteSettings !== null && $websiteSettings->getActiveHomepage() !== $this) {
            $websiteSettings->setActiveHomepage($this);
        }

        $this->websiteSettings = $websiteSettings;

        return $this;
    }

    public function __toString(): string
    {
        return $this->label;
    }
}
