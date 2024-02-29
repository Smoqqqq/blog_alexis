<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/create', name: 'app_article_create')]
    public function create(Request $request, EntityManagerInterface $em, ArticleRepository $articleRepository): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            $this->addFlash("success", "Article ajouté");

            return $this->redirectToRoute("app_article_search");
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route("/articles", name: 'app_article_search')]
    public function list(ArticleRepository $articleRepository): Response
    {
        return $this->render("article/list.html.twig", [
            'articles' => $articleRepository->findAll(),
        ]);
    }
}
