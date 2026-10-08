<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $ingredients = [];
        for ($i = 0; $i < 100; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom('ingr_' . $faker->word());
            $ingredient->setPrix($faker->randomFloat(2, 0, 200));
            $manager->persist($ingredient);
            $ingredients[] = $ingredient;
        }

        for ($i = 0; $i < 50; $i++) {
            $recette = new Recette();
            $recette->setNom(mb_substr($faker->sentence(3), 0, 100));
            $recette->setTemps($faker->numberBetween(5, 240));
            $recette->setDescription($faker->paragraph(4));
            $recette->setPrix($faker->randomFloat(2, 1, 100));
            $recette->setDifficulte($faker->numberBetween(0, 5));

            foreach ($faker->randomElements($ingredients, $faker->numberBetween(2, 10), false) as $ingredient) {
                $recette->addIngredient($ingredient);
            }

            $manager->persist($recette);
        }

        $manager->flush();
    }
}
