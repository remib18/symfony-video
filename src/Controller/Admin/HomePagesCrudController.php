<?php

namespace App\Controller\Admin;

use App\Entity\HomePages;
use App\Entity\WebsiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HomePagesCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->displayIf(function (HomePages $homePages) {
                $websiteSettings = $this->entityManager->getRepository(WebsiteSettings::class)->findOneBy([]);
                return $websiteSettings->getActiveHomepage() !== $homePages;
            });
        });

        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::INDEX);
        }
        return $actions;
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