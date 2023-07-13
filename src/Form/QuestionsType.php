<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => [
                    'placeholder' => 'Entrez la ville de destination'
                ],
            ])
            ->add('duree_sejour', IntegerType::class, [
                'label' => 'Durée du séjour',
                'attr' => [
                    'placeholder' => 'Entrez la durée de votre séjour (en jours)'
                ],
            ])
            ->add('nombre_personne_sejour', IntegerType::class, [
                'label' => 'Nombre de personne(s)',
                'attr' => [
                    'placeholder' => 'Entrez le nombre de personne(s) pour ce séjour'
                ],
            ])
            ->add('budget_sejour', IntegerType::class, [
                'label' => 'Votre budget',
                'attr' => [
                    'placeholder' => 'Entrez votre budget pour ce séjour (Budget par personne si vous voyagez en groupe)'
                ],
            ])
            ->add('saison_destination', ChoiceType::class, [
                'label' => 'Saison de destination',
                'choices' => [
                    'Été' => "Ete",
                    'Automne' => "Automne",
                    'Hiver' => "Hiver",
                    'Printemps' => "Printemps"
                ],
                'attr' => [
                    'placeholder' => 'Entrez la saison qu\'il fera lors de votre départ',
                ],
            ])
            ->add('interet_preference', TextareaType::class, [
                'label' => 'Vos intérêts & préférence',
                'attr' => [
                    'placeholder' => 'Saisissez en un mot ce que vous aimez faire durant vos vacances. (Mettez une virgule après chaque mot)'
                ],
            ])
            ->add('restrictions', TextareaType::class, [
                'label' => 'Vos restictions & avertions',
                'attr' => [
                    'placeholder' => 'Saisissez en un mot ce qu\'il y a à savoir sur vous concernant vos alergies alimentaires, vos aversions lors de vos voyages'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
