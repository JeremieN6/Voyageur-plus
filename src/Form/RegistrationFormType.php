<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Email'
            ])
            ->add('nom', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('prenom',TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('telephone',TextType::class,[
                'label' => 'N° Téléphone'
            ])
            ->add('adresse',TextType::class,[
                'label' => 'Adresse'
            ])
            ->add('code_postal',TextType::class,[
                'label' => 'Code Postal'
            ])
            ->add('ville',TextType::class,[
                'label' => 'Ville'
            ])
            ->add('pseudo',TextType::class,[
                'label' => 'Pseudo'
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décris toi en quelques mots. 🙂'
                ]
            ])
            ->add('ConsentementRGPD', CheckboxType::class, [
                'label' => 'J\'accepte que mes données soit collectées dans le cadre de la loi RGPD.',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter la charte RGPD.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de Passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Oups 🤭 ! Veuillez saisir un mot de passe s\'il vous plaît !',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
