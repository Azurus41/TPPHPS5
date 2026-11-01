<?php

namespace App\EventListener;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Ingredient::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Ingredient::class)]
class IngredientListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Ingredient $ingredient, PrePersistEventArgs $event): void
    {
        $ingredient->setSlug($this->slugger->slug($ingredient->getNom())->lower());
    }

    public function preUpdate(Ingredient $ingredient, PreUpdateEventArgs $event): void
    {
        $ingredient->setUpdatedAt(new \DateTimeImmutable());
        $ingredient->setSlug($this->slugger->slug($ingredient->getNom())->lower());

        // les champs modifiés ici doivent être pris en compte dans le même flush
        $em = $event->getObjectManager();
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($em->getClassMetadata(Ingredient::class), $ingredient);
    }
}
