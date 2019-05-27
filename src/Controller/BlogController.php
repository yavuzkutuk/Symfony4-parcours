<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blog", name="blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     *
     * @Route("/show/{slug}", defaults={"slug"="Article Sans Titre"}, requirements={"slug"="^[a-z0-9-]+$"}, name="blog_show")
     */
    public function show(string $slug)
    {
//        if(!$slug){
//            $slug = "Article Sans Titre";
//        }

        $slug = ucwords(str_replace('-', ' ', $slug));

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        $tags = $article->getTagId();

        $category = $article->getCategory();

        return $this->render('blog/show.html.twig', [
            'slug' => $slug,
            'tags' => $tags,
            'article' => $article,
            'category' => $category
        ]);
    }

    /**
     * @param string $categoryName
     * @return Response
     *  @Route("/category/{name}", name="show_category")
     */
    public function showByCategory(Category $category)
    {
        //$category = $this->getDoctrine()->getRepository( Category::class)->findOneBy(['name' => mb_strtolower($categoryName)]);;
        //$articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category'=>$category->getId()],null,3);
        $articles = $category->getArticles();

        return $this->render('blog/category.html.twig',
            [
                'category' => $category,
                'articles' => $articles
            ]
        );
    }

    /**
     * @Route("/tag/{name}", name="tag_show")
     */
    public function showTag(Tag $tag): Response
    {
        return $this->render('blog/tag.html.twig', [
            'article' => $tag -> getArticles(),
            'tag' => $tag -> getName()
        ]);
    }
}
