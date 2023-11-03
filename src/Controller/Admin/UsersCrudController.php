<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    /**/
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('pseudo'),
            TextField::new('password')->setFormType(PasswordType::class)->hideOnIndex(),
            IntegerField::new('telephone'),
            TextField::new('ville'),
            TextareaField::new('description')->onlyOnForms(),
            IntegerField::new('codePostal')->onlyOnForms(),
            TextField::new('adresse')->onlyOnForms(),
            ImageField::new('file')->setBasePath('%aws_s3_base_url%/upload/profil/images')->onlyOnIndex(),
            ImageField::new('file')->setBasePath('%aws_s3_base_url%/upload/profil/images')->onlyOnDetail(),
            ImageField::new('file')->setUploadDir('public/upload/images/profil')->onlyOnForms(),
            // ImageField::new('file')->setBasePath('upload/images/profil')->setUploadDir('public/upload/images/profil'),
            // TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            // ImageField::new('featured_image')->setBasePath('upload/images/featured')->onlyOnIndex(),
        ];
    }
    
}
