<?php

namespace App\Controller;

use App\Entity\WebsiteSettings;
use App\Form\WebsiteSettingsType;
use App\Repository\WebsiteSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/website/settings')]
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
            $entityManager->persist($websiteSetting);
            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/new.html.twig', [
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

    #[Route('/{id}/edit', name: 'app_website_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebsiteSettings $websiteSetting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebsiteSettingsType::class, $websiteSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_website_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('website_settings/edit.html.twig', [
            'website_setting' => $websiteSetting,
            'form' => $form,
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
}
