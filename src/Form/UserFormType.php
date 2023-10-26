<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'invalid_message' => 'Attention, la taille de l\'image ne doit pas dépasser 2 Mo. ⛔',
                'required' => false,
                // 'mapped' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => true,                  
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entre ton nouveau Nom 🙂']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entre ton nouveau prénom 🙂']
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => ['placeholder' => 'Entre ton nouveau numéro de téléphone 🙂']
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Entre ta nouvelle adresse 📍']
            ])
            ->add('code_postal', NumberType::class, [
                'label' => 'Code Postal',
                'attr' => ['placeholder' => 'Entre ton code postal 📍']
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Entre ta nouvelle ville 📍']
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Entre ton nouveau pseudo 🙂']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entre une courte description de toi 📰']
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Mettre à jour',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Users::class,
        ]);
    }
}
