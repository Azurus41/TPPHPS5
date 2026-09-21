<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\IngredientFormType;
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
}
