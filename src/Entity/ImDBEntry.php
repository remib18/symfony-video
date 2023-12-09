<?php

namespace App\Entity;

use App\Repository\ImDBEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImDBEntryRepository::class)]
class ImDBEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imDB_id = null;

    #[ORM\Column(length: 255)]
    private ?string $imDB_title = null;

    #[ORM\Column(length: 255)]
    private ?string $imDB_image_url = null;

    #[ORM\Column]
    private ?bool $is_serie = null;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $category_id;

    public function __construct()
    {
        $this->category_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImDBId(): ?string
    {
        return $this->imDB_id;
    }

    public function setImDBId(string $imDB_id): static
    {
        $this->imDB_id = $imDB_id;

        return $this;
    }

    public function getImDBTitle(): ?string
    {
        return $this->imDB_title;
    }

    public function setImDBTitle(string $imDB_title): static
    {
        $this->imDB_title = $imDB_title;

        return $this;
    }

    public function getImDBImageUrl(): ?string
    {
        return $this->imDB_image_url;
    }

    public function setImDBImageUrl(string $imDB_image_url): static
    {
        $this->imDB_image_url = $imDB_image_url;

        return $this;
    }

    public function isIsSerie(): ?bool
    {
        return $this->is_serie;
    }

    public function setIsSerie(bool $is_serie): static
    {
        $this->is_serie = $is_serie;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoryId(): Collection
    {
        return $this->category_id;
    }

    public function addCategoryId(Category $categoryId): static
    {
        if (!$this->category_id->contains($categoryId)) {
            $this->category_id->add($categoryId);
        }

        return $this;
    }

    public function removeCategoryId(Category $categoryId): static
    {
        $this->category_id->removeElement($categoryId);

        return $this;
    }
    public function getGenresNames(): array
    {
        return $this->category_id->map(function (Category $category) {
            return $category->getName();
        })->toArray();
    }
}
