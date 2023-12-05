<?php

namespace App\Controller\Admin;

use App\Entity\WebsiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class WebsiteSettingsCrudController extends AbstractCrudController
{
    private $em;

    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $em)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->em = $em;
    }

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

    public function configureActions(Actions $actions): Actions
    {
         $actions
            ->disable(Action::NEW, Action::DELETE, Action::SAVE_AND_RETURN)
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-save')->setLabel('Save');
            });

        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::INDEX);
        }
         return $actions;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof WebsiteSettings) {
            $url = $this->adminUrlGenerator
                ->setController(WebsiteSettingsCrudController::class)
                ->setAction('edit')
                ->setEntityId($entityInstance->getId())
                ->generateUrl();

            $this->redirect($url);
        }
    }
}