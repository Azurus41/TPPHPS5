<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $admin = new User();
        $admin->setEmail('admin@a.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setNom($faker->lastName());
        $admin->setPrenom($faker->firstName());
        $admin->setVille($faker->city());
        $admin->setCp($faker->postcode());
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $manager->persist($admin);

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail());
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());
            $user->setVille($faker->city());
            $user->setCp($faker->postcode());
            $user->setPassword($this->hasher->hashPassword($user, '1234'));
            $manager->persist($user);
        }

        $ingredients = [];
        for ($i = 0; $i < 100; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom('ingr_' . $faker->word());
            $ingredient->setPrix($faker->randomFloat(2, 0, 200));
            $ingredient->setSlug('test slug');
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
