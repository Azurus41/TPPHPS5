<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteFormType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette_index')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Toutes les recettes et leurs ingrédients',
            'recettes' => $recetteRepository->find_all_recettes_avec_ingredients(),
        ]);
    }

    #[Route('/recette/create', name: 'recette.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recette();
        $crea_form = $this->createForm(RecetteFormType::class, $recette);

        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a bien été créée avec succès !');

            return $this->redirectToRoute('app_recette_index');
        }

        return $this->render('recette/create.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/recette/ingredient', name: 'recette.ingredient')]
    public function index_recette_ingredient(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les 10 premières recettes et leurs ingrédients (QueryBuilder)',
            'recettes' => $recetteRepository->find_recettes_avec_ingredients(),
        ]);
    }

    #[Route('/recette/ingredient_sql', name: 'recette.ingredient_sql')]
    public function index_recette_ingredient_sql(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les 10 premières recettes et leurs ingrédients (SQL)',
            'recettes' => $recetteRepository->find_recettes_avec_ingredients_sql(),
        ]);
    }

    #[Route('/recette/ingredient_dql', name: 'recette.ingredient_dql')]
    public function index_recette_ingredient_dql(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les 10 premières recettes et leurs ingrédients (DQL)',
            'recettes' => $recetteRepository->find_recettes_avec_ingredients_dql(),
        ]);
    }

    #[Route('/recette/avec_5_ingredients', name: 'recette.avec_5_ingredients')]
    public function index_recette_avec_5_ingredients(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les recettes qui ont 5 ingrédients (QueryBuilder)',
            'recettes' => $recetteRepository->find_recettes_avec_5_ingredients(),
        ]);
    }

    #[Route('/recette/avec_5_ingredients_sql', name: 'recette.avec_5_ingredients_sql')]
    public function index_recette_avec_5_ingredients_sql(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les recettes qui ont 5 ingrédients (SQL)',
            'recettes' => $recetteRepository->find_recettes_avec_5_ingredients_sql(),
        ]);
    }

    #[Route('/recette/avec_5_ingredients_dql', name: 'recette.avec_5_ingredients_dql')]
    public function index_recette_avec_5_ingredients_dql(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'titre' => 'Les recettes qui ont 5 ingrédients (DQL)',
            'recettes' => $recetteRepository->find_recettes_avec_5_ingredients_dql(),
        ]);
    }
}
