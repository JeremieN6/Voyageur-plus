<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            IdField::new('id'),
            EmailField::new('email'),
            TextField::new('nom'),
            TextField::new('prenom'),
            IntegerField::new('telephone'),
            TextField::new('ville'),
            ImageField::new('file')->setBasePath('upload/images/profil')->setUploadDir('public/upload/images/profil'),
            // TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            // ImageField::new('featured_image')->setBasePath('upload/images/featured')->onlyOnIndex(),
        ];
    }
    
}
