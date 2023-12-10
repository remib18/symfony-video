<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Contact;
use App\Entity\WebsiteSettings;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'content' => $this->getHomeMarkdownContent($entityManager),
            'form'=> $this->getContactForm($entityManager, $request)->createView(),
            'posts' => $this->getLastBlogPosts($entityManager),
        ]);
    }

    private function getHomeMarkdownContent(EntityManagerInterface $entityManager): string
    {
        $websiteSettings = $entityManager->getRepository(WebsiteSettings::class)->findOneBy([]);

        if ($websiteSettings === null) {
            throw $this->createNotFoundException('La configuration du site n\'a pas été trouvée.');
        }

        $activeHomepage = $websiteSettings->getActiveHomepage();
        if ($activeHomepage !== null) {
            $markdownContent = $activeHomepage->getMarkdown();
        } else {
            $markdownContent = 'Aucun contenu n\'est disponible pour le moment.';
        }

        // Configuration du convertisseur Markdown
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        // Convertion du contenu Markdown en HTML
        $converter = new MarkdownConverter($environment);
        try {
            $htmlContent = $converter->convert($markdownContent);
        } catch (CommonMarkException) {
            $htmlContent = '<p>Une erreur s\'est produite lors de la conversion du contenu.</p>';
        }

        return $htmlContent;
    }

    private function getContactForm(EntityManagerInterface $entityManager, Request $request): FormInterface
    {
        $contact= new Contact();
        $form= $this->createForm(ContactType::class,$contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();
           $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success','Message envoyé!');

           // return $this ->redirectToRoute('home/index');

        }

        return $form;
    }

    private function getLastBlogPosts(EntityManagerInterface $entityManager): array
    {
        return $entityManager->getRepository(BlogPost::class)->findLatest();
    }
}