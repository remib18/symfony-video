<?php

namespace App\Controller\Admin;

use App\Entity\HomePages;
use App\Entity\User;
use App\Entity\WebsiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        }

        if ($this->isGranted('ROLE_WEBMASTER')) {
            //faire redirect to webmasters page
            return parent::index();
        }

        return $this->redirectToRoute('app_app');
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            ->setTitle('Symfony Video');
    }

    public function configureMenuItems(): iterable {

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
            yield MenuItem::linkToCrud('HomePages', 'fa fa-file', HomePages::class);
            $websiteSettingsId = $this->em->getRepository(WebsiteSettings::class)->findDefault()->getId();
            yield MenuItem::linkToCrud('WebsiteSettings', 'fa fa-cog', WebsiteSettings::class)
                ->setAction('edit')
                ->setEntityId($websiteSettingsId);
        }
    }
}