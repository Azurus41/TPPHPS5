<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom($faker->word());
            $ingredient->setPrix((float) number_format((float) mt_rand(0, 20000) / 100, 2, '.', ''));
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
