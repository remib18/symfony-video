<?php

namespace App\Controller\Admin;

use App\Entity\HomePages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HomePagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomePages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label'),
            TextField::new('markdown')->setFormType(TextareaType::class)->setRequired(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('HomePage')
            ->setEntityLabelInPlural('HomePages')
            ->setSearchFields(['label', 'markdown'])
            ->setDefaultSort(['id' => 'DESC']);
    }
}