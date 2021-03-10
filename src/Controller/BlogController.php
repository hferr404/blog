<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */

    public function home(): Response
    {
        return $this->render('blog/home.html.twig',[
        'title' => 'Bienvenue sur le Blog Symfony',
        'age' => 25]);
    }
    
    /**
     * @Route("/blog", name="blog")
     */

     /*
            Pour selectionner des données dans une table SQL, nous devons absolument avoir accès à la classe Repository de l'entité correspondante 
            Un Repository est une classe permettant uniquement d'executer des requetes de selection en BDD (SELECT)
            Nous devons donc accéder au repository de l'netité Article au sein de notre controller  

            On appel l'ORM doctrine (getDoctrine()), puis on importe le repositoritory de la classe Article grace à la méthode getRepository()
            $repo est un objet issu de la classe ArticleRepository
            cet objet contient des méthodes permettant d'executer des requetes de selections
            findAll() : méthode issue de la classe ArticleRepository permettant de selectionner l'ensemble de la table SQL 'Article'
        */
   // on envoie sur le template, les articles selectionnés en BDD afin de 
   //pouvoir les afficher dynamiquement sur le template à l'aide du langage Twig


    public function index(ArticleRepository $repo): Response
    {
       // $repo = $this->getDoctrine()->getRepository(Article::class);

        dump($repo);

        $article = $repo->findAll();

        dump($article);

        return $this->render('blog/index.html.twig', [
            'title' => 'Listes des articles',
            'article' => $article
        ]);
    }

    /**
      * @Route("/blog/new", name="blog_create")
      */
      public function create(Request $request): Response
      {
          dump($request);
          return $this->render("blog/create.html.twig");
      }


    /**
     * @Route("/blog/{id}", name="blog_show")
     */

     public function show(Article $article): Response
     {
        // $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        // dump($repoArticle);
        // dump($id);

// On transmet à la méthode find() de la classe ArticleRepository l'id recupéré dans l'URL et transmit en argument de la fonction show($id) | $id = 3
// La méthode find() permet de selectionner en BDD un article par son ID
// on envoi sur le template les données selectionnées en BDD, c'est à dire les informations d'1 article en fonction l'id transmit dans l'URL


       //  $article = $repoArticle->find($id);
         dump($article);
         return $this->render('blog/show.html.twig', [
             "articleTwig" => $article
         ]);


    /*
        En fonction de la route paramétrée {id} et de l'injection de dépendance $article, Symfony voit que l'on besoin 
        d'un article de la BDD par rapport à l'ID transmit dans l'URL, il est donc capable de recupérer l'ID et 
        de selectionner en BDD l'article correspondant et de l'envoyer directement en argument de la méthode show(Article $article)
        Tout ça grace à des ParamConverter qui appel des convertisseurs pour convertir les paramètres de l'objet. 
        Ces objets sont stockés en tant qu'attribut de requete et peuvent donc être injectés an tant qu'argument de méthode de controller
    */

     }

     
}
