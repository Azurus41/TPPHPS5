<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\IngredientFormType;
use App\Form\IngredientFormType_v3;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient_index')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/tomate', name: 'ingredient.tomate')]
    public function index_ingredient_tomate(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate(),
        ]);
    }

    #[Route('/ingredient/tomate_5', name: 'ingredient.tomate_5')]
    public function index_ingredient_tomate_5(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate_5(),
        ]);
    }

    #[Route('/ingredient/tom', name: 'ingredient.tom')]
    public function index_ingredient_tom(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tom5(),
        ]);
    }

    #[Route('/ingredient/by_price/{prix}', name: 'ingredient.by_price')]
    public function index_ingredient_by_price(float $prix, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price($prix),
        ]);
    }

    #[Route('/ingredient/by_price/{prix}/by_name/{nom}', name: 'ingredient.by_price_and_name')]
    public function index_ingredient_by_price_and_name(float $prix, string $nom, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price_and_name($prix, $nom),
        ]);
    }

    #[Route('/ingredient/sql', name: 'ingredient.sql')]
    public function index_sql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll_sql(),
        ]);
    }

    #[Route('/ingredient/tomate_sql', name: 'ingredient.tomate_sql')]
    public function index_ingredient_tomate_sql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate_sql(),
        ]);
    }

    #[Route('/ingredient/tomate_5_sql', name: 'ingredient.tomate_5_sql')]
    public function index_ingredient_tomate_5_sql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate_5_sql(),
        ]);
    }

    #[Route('/ingredient/tom_sql', name: 'ingredient.tom_sql')]
    public function index_ingredient_tom_sql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tom_sql(),
        ]);
    }

    #[Route('/ingredient/by_price_sql/{prix}', name: 'ingredient.by_price_sql')]
    public function index_ingredient_by_price_sql(float $prix, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price_sql($prix),
        ]);
    }

    #[Route('/ingredient/by_price_sql/{prix}/by_name/{nom}', name: 'ingredient.by_price_and_name_sql')]
    public function index_ingredient_by_price_and_name_sql(float $prix, string $nom, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price_and_name_sql($prix, $nom),
        ]);
    }

    #[Route('/ingredient/dql', name: 'ingredient.dql')]
    public function index_dql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll_dql(),
        ]);
    }

    #[Route('/ingredient/tomate_dql', name: 'ingredient.tomate_dql')]
    public function index_ingredient_tomate_dql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate_dql(),
        ]);
    }

    #[Route('/ingredient/tomate_5_dql', name: 'ingredient.tomate_5_dql')]
    public function index_ingredient_tomate_5_dql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tomate_5_dql(),
        ]);
    }

    #[Route('/ingredient/tom_dql', name: 'ingredient.tom_dql')]
    public function index_ingredient_tom_dql(IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_tom_dql(),
        ]);
    }

    #[Route('/ingredient/by_price_dql/{prix}', name: 'ingredient.by_price_dql')]
    public function index_ingredient_by_price_dql(float $prix, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price_dql($prix),
        ]);
    }

    #[Route('/ingredient/by_price_dql/{prix}/by_name/{nom}', name: 'ingredient.by_price_and_name_dql')]
    public function index_ingredient_by_price_and_name_dql(float $prix, string $nom, IngredientRepository $ingredientRepository): Response
    {
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->find_ingredient_by_price_and_name_dql($prix, $nom),
        ]);
    }

    #[Route('/ingredient/greater_than_100', name: 'app_ingredient_greater_than_100')]
    public function index_only_greater_than_100(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();
        $ingredients_100 = [];
        foreach ($ingredients as $ingredient) {
            if ($ingredient->getPrix() > 100) {
                $ingredients_100[] = $ingredient;
            }
        }

        return $this->render('ingredient/greater_than_100.html.twig', [
            'ingredients' => $ingredients_100,
        ]);
    }

    #[Route('/ingredient/greater_than_100_v2', name: 'app_ingredient_greater_than_100_v2')]
    public function index_only_greater_than_100_v2(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = new ArrayCollection($ingredientRepository->findAll());
        $ingredients_100 = $ingredients->filter(function (Ingredient $ingredient) {
            return $ingredient->getPrix() > 100;
        });

        return $this->render('ingredient/greater_than_100_v2.html.twig', [
            'ingredients' => $ingredients_100,
        ]);
    }

    #[Route('/ingredient/greater_than_100_v3', name: 'app_ingredient_greater_than_100_v3')]
    public function index_only_greater_than_100_v3(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = new ArrayCollection($ingredientRepository->findAll());
        $criteria = Criteria::create()->where(Criteria::expr()->gt('prix', 100));
        $ingredients_100 = $ingredients->matching($criteria);

        return $this->render('ingredient/greater_than_100_v3.html.twig', [
            'ingredients' => $ingredients_100,
        ]);
    }

    #[Route('/ingredient/greater_than_100_v4', name: 'app_ingredient_greater_than_100_v4')]
    public function index_only_greater_than_100_v4(IngredientRepository $ingredientRepository): Response
    {
        $criteria = Criteria::create()->where(Criteria::expr()->gt('prix', 100));
        $ingredients_100 = $ingredientRepository->matching($criteria);

        return $this->render('ingredient/greater_than_100_v4.html.twig', [
            'ingredients' => $ingredients_100,
        ]);
    }

    #[Route('/ingredient/create', name: 'ingredient.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $ingredient->setNom('poivre');
        $ingredient->setPrix(10);

        $crea_form = $this->createFormBuilder($ingredient)
            ->setAction($this->generateUrl('ingredient.store'))
            ->setMethod('POST')
            ->add('nom', TextType::class)
            ->add('prix', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer'])
            ->getForm();

        return $this->render('ingredient/create.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/store', name: 'ingredient.store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        $ingredient = new Ingredient();
        $ingredient->setNom($data['form']['nom']);
        $ingredient->setPrix((float) $data['form']['prix']);

        $entityManager->persist($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('app_ingredient_index');
    }

    #[Route('/ingredient/create_and_store', name: 'ingredient.create_and_store', methods: ['GET', 'POST'])]
    public function create_and_store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $crea_form = $this->createFormBuilder($ingredient)
            ->setAction($this->generateUrl('ingredient.create_and_store'))
            ->setMethod('POST')
            ->add('nom', TextType::class)
            ->add('prix', NumberType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer'])
            ->getForm();

        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            $data = $crea_form->getData();
            $ingredient->setNom($data->getNom());
            $ingredient->setPrix($data->getPrix());

            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_ingredient_index');
        }

        return $this->render('ingredient/create_and_store.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/create_and_store_v2', name: 'ingredient.create_and_store_v2', methods: ['GET', 'POST'])]
    public function create_and_store_v2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $crea_form = $this->createForm(IngredientFormType::class, $ingredient);

        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été créé avec succès !');

            return $this->redirectToRoute('app_ingredient_index');
        }

        return $this->render('ingredient/create_v2.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/create_and_store_v3', name: 'ingredient.create_and_store_v3', methods: ['GET', 'POST'])]
    public function create_and_store_v3(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = new Ingredient();
        $crea_form = $this->createForm(IngredientFormType_v3::class, $ingredient, [
            'submit_label' => 'Créer l\'ingrédient',
        ]);

        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ingrédient a bien été créé avec succès !');

            return $this->redirectToRoute('app_ingredient_index');
        }

        return $this->render('ingredient/create_v3.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'PUT'])]
    public function edit(int $id, IngredientRepository $ingredientRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingredient = $ingredientRepository->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException('Ingrédient non trouvé.');
        }

        $form = $this->createForm(IngredientFormType_v3::class, $ingredient, [
            'method' => 'PUT',
            'submit_label' => 'Enregistrer les modifications',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient2 = $form->getData();

            $entityManager->persist($ingredient2);
            $entityManager->flush();

            $this->addFlash('success', 'Votre ingrédient a été modifié avec succès !');

            return $this->redirectToRoute('app_ingredient_index');
        }

        return $this->render('ingredient/create_v3.html.twig', [
            'crea_form' => $form->createView(),
        ]);
    }

    #[Route('/ingredient/{id}', name: 'ingredient.delete', methods: ['DELETE'])]
    public function delete(int $id, IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager): Response
    {
        $ingredient = $ingredientRepository->find($id);

        if (!$ingredient) {
            throw $this->createNotFoundException('Ingrédient non trouvé.');
        }

        $entityManager->remove($ingredient);
        $entityManager->flush();

        $this->addFlash('success', 'Votre ingrédient a été supprimé avec succès !');

        return $this->redirectToRoute('app_ingredient_index');
    }
}