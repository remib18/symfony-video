<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function index(AdminContext $context)
    {
        if ($this->isGranted('ROLE_WEBMASTER') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }


    return parent::index($context);
}
    public function __construct(

        public UserPasswordHasherInterface $userPasswordHashed
    ) {}
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::INDEX);
        }
        return $actions;
    }


    public function configureFields(string $pageName): iterable
    {



            return [
                IdField::new('id')->hideOnForm(),
                EmailField::new('email'),
                ChoiceField::new('roles')
                    ->allowMultipleChoices()
                    ->setChoices([
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                        'WebMaster'=> 'ROLE_WEBMASTER'
                    ])
                    ->renderAsBadges()
                    ->formatValue(function ($value, $entity) {
                        if ($entity instanceof User){
                        return $entity->getRoleAsString();
                        }
                        return null;
                    }),
                TextField::new('password')
                    ->setFormType(RepeatedType::class)
                    ->setFormTypeOptions([
                        'type' => PasswordType::class,
                        'first_options' => ['label' => 'Password'],
                        'second_options' => ['label' => '(Repeat)'],
                        'mapped' => false,
                    ])
                    ->setRequired($pageName === Crud::PAGE_NEW)
                    ->onlyOnForms(),
                TextField::new('firstname'),
                TextField::new('lastname'),
                BooleanField::new('isBanned')
                // ImageLink si vous voulez l'inclure
            ];





        }
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword(): \Closure
    {
        return function(FormEvent $event) {
            /** @var User $user */
            $user = $event->getData();
            $form = $event->getForm();

            $password = $form->get('password')->getData();
            if (!$password) {
                return;
            }

            $hash = $this->userPasswordHashed->hashPassword($user, $password);
            $user->setPassword($hash);
        };
    }




}
