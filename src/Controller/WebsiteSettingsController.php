<?php

namespace App\Controller;

use App\Entity\HomePages;
use App\Entity\WebsiteSettings;
use App\Form\HomePagesType;
use App\Form\WebsiteSettingsType;
use App\Repository\HomePagesRepository;
use App\Repository\WebsiteSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**#[Route('/website/settings')]
class WebsiteSettingsController extends AbstractController
{
    #[Route('/', name: 'app_website_settings_index', methods: ['GET'])]
    public function index(WebsiteSettingsRepository $websiteSettingsRepository): Response
    {
        return $this->render('website_settings/index.html.twig', [
            'website_settings' => $websiteSettingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_website_settings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $websiteSetting = new WebsiteSettings();
        $form = $this->createForm(WebsiteSettingsType::class, $websiteSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez la valeur du champ markdownContent
            $markdownContent = $form->get('markdownContent')->getData();

            // Check if a HomePages entity is associated
            if ($websiteSetting->getActiveHomepage() === null) {
                // Create a new HomePages entity if it doesn't exist
                $homepage = new HomePages();
                $homepage->setLabel('Homepage');
                $websiteSetting->setActiveHomepage($homepage);
            }

            // Enregistrez le contenu Markdown dans l'entité HomePages associée
            $websiteSetting->getActiveHomepage()->setMarkdown($markdownContent);

            $entityManager->persist($websiteSetting);
            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/new.html.twig', [
            'website_setting' => $websiteSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_website_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebsiteSettings $websiteSetting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebsiteSettingsType::class, $websiteSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérez la valeur du champ markdownContent
            $markdownContent = $form->get('markdownContent')->getData();

            // Enregistrez le contenu Markdown dans l'entité HomePages associée
            $websiteSetting->getActiveHomepage()->setMarkdown($markdownContent);

            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/edit.html.twig', [
            'website_setting' => $websiteSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_website_settings_show', methods: ['GET'])]
    public function show(WebsiteSettings $websiteSetting): Response
    {
        return $this->render('website_settings/show.html.twig', [
            'website_setting' => $websiteSetting,
        ]);
    }

    #[Route('/{id}', name: 'app_website_settings_delete', methods: ['POST'])]
    public function delete(Request $request, WebsiteSettings $websiteSetting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$websiteSetting->getId(), $request->request->get('_token'))) {
            $entityManager->remove($websiteSetting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
    }
}*/

#[Route('/website/settings')]
class WebsiteSettingsController extends AbstractController
{
    /**#[Route('/', name: 'app_website_settings_index', methods: ['GET'])]
    public function index(HomePagesRepository $homePagesRepository): Response
    {
        return $this->render('website_settings/index.html.twig', [
            'home_pages' => $homePagesRepository->findAll(),
        ]);
    }*/

    #[Route('/', name: 'app_website_settings_index', methods: ['GET'])]
    public function index(HomePagesRepository $homePagesRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'entité WebsiteSettings de la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->find(1);

        // Vérifiez si l'entité WebsiteSettings existe
        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        // Récupérez la page d'accueil active
        $activeHomepage = $websiteSettings->getActiveHomepage();

        return $this->render('website_settings/index.html.twig', [
            'home_pages' => $homePagesRepository->findAll(),
            'active_homepage' => $activeHomepage,
        ]);
    }

    #[Route('/new', name: 'app_website_settings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $homePage = new HomePages();
        $form = $this->createForm(HomePagesType::class, $homePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($homePage);
            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/new.html.twig', [
            'home_page' => $homePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_website_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HomePages $homePage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HomePagesType::class, $homePage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/edit.html.twig', [
            'home_page' => $homePage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_website_settings_delete', methods: ['POST'])]
    public function delete(Request $request, HomePages $homePage, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'entité WebsiteSettings de la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->find(1);

        // Vérifiez si l'entité WebsiteSettings existe
        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        // Vérifiez si la HomePage que vous essayez de supprimer est la HomePage active
        if ($websiteSettings->getActiveHomepage() === $homePage) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer la HomePage active.');
            return $this->redirectToRoute('app_website_settings_index');
        }

        if ($this->isCsrfTokenValid('delete'.$homePage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($homePage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/set-active-homepage/{id}', name: 'app_website_settings_set_active_homepage', methods: ['POST'])]
    public function setActiveHomepage(HomePages $homePage, EntityManagerInterface $entityManager): Response
    {

        // Récupérez l'entité WebsiteSettings de la base de données
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->find(1);

        // Vérifiez si l'entité WebsiteSettings existe
        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        // Set the active homepage
        $websiteSettings->setActiveHomepage($homePage);

        // Persist the changes
        $entityManager->persist($websiteSettings);

        // Save the changes
        $entityManager->flush();

        return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
    }
}