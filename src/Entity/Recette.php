<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\RecetteProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['recette:read']],
    denormalizationContext: ['groups' => ['recette:write']],
    // Mission 36 : modification / suppression réservées à l'auteur et à l'administrateur
    operations: [
        new GetCollection(),
        new Get(),
        new Post(processor: RecetteProcessor::class),
        new Put(security: "is_granted('ROLE_ADMIN') or object.getAuteur() == user"),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.getAuteur() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getAuteur() == user"),
    ],
)]
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[Assert\NotNull]
    #[Assert\Positive(message: 'Le temps doit être supérieur à 0 minute.')]
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\Column]
    private ?int $temps = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Assert\NotBlank]
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\PositiveOrZero(message: 'Le prix ne peut pas être négatif.')]
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[Assert\Range(min: 0, max: 5, notInRangeMessage: 'La difficulté doit être comprise entre {{ min }} et {{ max }}.')]
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $difficulte = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[Groups(['recette:read', 'recette:write'])]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recettes')]
    private Collection $ingredients;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $auteur = null;

    /**
     * @var Collection<int, Tag>
     */
    #[Assert\Valid]
    #[ORM\OneToMany(targetEntity: Tag::class, mappedBy: 'recette', cascade: ['persist'], orphanRemoval: true)]
    private Collection $tags;

    public function __construct()
    {
        // createdAt est affecté dans setCreatedAtValue() (PrePersist)
        // $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->ingredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(int $temps): static
    {
        $this->temps = $temps;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(?int $difficulte): static
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setRecette($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag) && $tag->getRecette() === $this) {
            $tag->setRecette(null);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }
}
