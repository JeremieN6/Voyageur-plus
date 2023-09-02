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
                'label' => 'Quelle est votre destination ?',
                'attr' => [
                    'placeholder' => 'Saisissez la ville de destination'
                ],
            ])
            ->add('duree_sejour', IntegerType::class, [
                'label' => 'Quelle est la durée du séjour ?',
                'attr' => [
                    'placeholder' => 'Entrez la durée en jours'
                ],
            ])
            ->add('nombre_personne_sejour', IntegerType::class, [
                'label' => 'Combien il y a t-il de personne pour ce séjour ?',
                'attr' => [
                    'placeholder' => 'Entrez le nombre de personne(s)'
                ],
            ])
            ->add('budget_sejour', IntegerType::class, [
                'label' => 'Quel est votre budget ? (Budget par personne si vous voyagez en groupe)',
                'attr' => [
                    'placeholder' => 'Saisissez votre budget (en euros "€")'
                ],
            ])
            ->add('mobilite_sejour', ChoiceType::class, [
                'label' => 'Serez vous véhiculé lors de votre voyage ?',
                'choices' => [
                    'Voiture personnel' => "vehiculePerso",
                    'Transport en commun (Bus, train, taxi, Uber etc)' => "transportCommun",
                    'Location de véhicule sur place' => "locationVehicule",
                    'Vélo, trotinette, roller' => "petiteLocomotion",
                    'Autre' => "autre"
                ],
            ])
            ->add('saison_destination', ChoiceType::class, [
                'label' => 'Quelle est la saison de votre destination au moment de votre voyage ?',
                'choices' => [
                    'Été' => "Ete",
                    'Automne' => "Automne",
                    'Hiver' => "Hiver",
                    'Printemps' => "Printemps"
                ],
            ])
            ->add('interet_preference', TextareaType::class, [
                'label' => 'Quelles sont vos centres d\'intérêt, vos préférences lorsque vous voyagez ?',
                'attr' => [
                    'placeholder' => 'Séparez d\'une virgule après chaque mot'
                ],
            ])
            ->add('restrictions', TextareaType::class, [
                'label' => 'Quelles sont vos restrictions, vos aversions ?',
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
