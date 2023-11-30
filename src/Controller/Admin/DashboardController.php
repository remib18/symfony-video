<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        if ($this->isGranted('ROLE_WEBMASTER')) {
            //TODO:redirect to webmasters page
            return $this->redirectToRoute('app_app');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
        }

        return $this->redirectToRoute('app_app');

    }

        public
        function configureDashboard(): Dashboard
        {
            return Dashboard::new()
                ->setTitle('Symfony Video');
        }

        public
        function configureMenuItems(): iterable
        {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
            if ($this->isGranted('ROLE_ADMIN')) {
                yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
            }

        }
    }
