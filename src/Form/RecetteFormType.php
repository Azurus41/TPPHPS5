<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class RecetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('temps', IntegerType::class, [
                'help' => 'Temps en minutes.',
                'attr' => ['min' => 1],
            ])
            ->add('description', TextareaType::class)
            ->add('prix', NumberType::class, [
                'required' => false,
                'attr' => ['min' => 0, 'step' => '0.01'],
            ])
            ->add('difficulte', IntegerType::class, [
                'required' => false,
                'help' => 'De 0 à 5.',
                'attr' => ['min' => 0, 'max' => 5],
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'help' => 'Choisissez exactement 3 ingrédients (Ctrl + clic pour en sélectionner plusieurs).',
                'constraints' => [
                    new Count(exactly: 3, exactMessage: 'Une recette doit avoir exactement 3 ingrédients.'),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Créer la recette'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
