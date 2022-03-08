<?php

namespace App\Controller\Admin\CRUD;

use App\Entity\Designer;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DesignerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Designer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            SlugField::new('slug')
                ->setTargetFieldName('name'),
            TextField::new('link'),
            ImageField::new('image')
                ->setBasePath('uploads/designers')
                ->setUploadDir('public/uploads/designers')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }
}
