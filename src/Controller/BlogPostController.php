<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogPostController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function blogIndex(): Response
    {
        return $this->redirectToRoute('app_blog_post_index');
    }

    #[Route('/post/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAllOrderedByPublishedDate(),
        ]);
    }

    #[Route('/post/{slug}', name: 'app_blog_post_show', methods: ['GET'])]
    public function show(string $slug, EntityManagerInterface $entityManager): Response
    {
        $blogPost = $entityManager
            ->getRepository(BlogPost::class)
            ->findOneBy(['slug' => $slug]);

        if(!$blogPost) {
            throw $this->createNotFoundException('The blog post does not exist');
        }

        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }
}
