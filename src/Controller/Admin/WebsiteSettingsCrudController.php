<?php

namespace App\Controller\Admin;

use App\Entity\WebsiteSettings;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class WebsiteSettingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebsiteSettings::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('active_homepage')->setRequired(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('WebsiteSetting')
            ->setEntityLabelInPlural('WebsiteSettings')
            ->setSearchFields(['active_homepage'])
            ->setDefaultSort(['id' => 'DESC']);
    }
}