<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
      * @Route("/blog/{id}/edit", name="blog_edit")
      */
      public function create(Article $articleCr = null, Request $request, EntityManagerInterface $manager): Response
      {
          dump($request);

        // Si la variable $articleCreate N'EST PAS, si elle ne contient aucun article de la BDD, 
        // cela veut dire nous avons envoyé la route '/blog/new', c'est une insertion, on entre dans le IF et on
        // crée une nouvelle instance de l'entité Article, création d'un nouvel article
        // Si la variable $articleCreate contient un article de la BDD, cela veut dire que 
        // nous avons envoyé la route '/blog/id/edit', c'est une modifiction d'article, on entre pas dans le IF


          if(!$articleCr)  
          {  
             $articleCr = new Article;
          }  

          $articleCr->setTitle('Article chelou')
                    ->setContent('contenu bizare');  

//           if($request->request->count())
//           {


// // $request permet de stocker les données des superglobales, la propriété $request->request 
// //permet de stocker les données véhiculées par un formulaire ($_POST), ici on compte si 
// //il y a données qui ont été saisie dans la formulaire
// // Pour insérer dans la table Article, nous devons instancier un objet issu de la classe entité Article, 
// //qui est lié à la table SQL Article
// // On rensigne tout les setteurs de l'objet avec en arguments les données du formulaire ($_POST)
// // on observe que l'objet entité Article $articleCreate, les propriétés contiennent bien les données du formaulaire
// // On fait appel au manager afin de pouvoir executer une insertion en BDD
// // on prépare et garde en mémoire l'insertion
// // on execute l'insertion

//               $article = new Article;

//               $article->setTitle($request->request->get('title'))
//                       ->setContent($request->request->get('content')) 
//                       ->setImgae($request->request->get('imgae')) 
//                       ->setCreatedAt(new \DateTime);

//                       dump($article);

//                       $manager->persist($article);
//                       $manager->flush();


// // Après l'insertion, on redirige l'internaute vers le détail de l'article qui vient d'être inséré en BDD
// // Cela correspond à la route 'blog_show', mais c'est une route paramétrée qui attend un ID dans l'URL
// // En 2ème argument de redirectToRoute, nous transmettons l'ID de l'article qui vient d'être inséré en BDD
                  
//               return $this->redirectToRoute('blog_show', [
//                   'id' => $article->getId()
//               ]);       
        //   }
          
        
        
        // $form = $this->createFormBuilder($article)
        //              ->add('title')
        //              ->add('content')
        //              ->add('imgae')
        //              ->getForm();  

        $form = $this->createForm(ArticleFormType::class, $articleCr);

        $form->handleRequest($request);

        dump($articleCr);

        if($form->isSubmitted() && $form->isValid())
        {

            if(!$articleCr->getId())
            {
                $articleCr->setCreatedAt(new \DateTime);
            }                                

            $manager->persist($articleCr);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                "id" => $articleCr->getId()
            ]);
        }

          return $this->render("blog/create.html.twig", [
              'formArticle' => $form->createView(),
              'editMode' => $articleCr->getId()
          ]);
      }


    /**
     * @Route("/blog/{id}", name="blog_show")
     */

     public function show(Article $articleCr): Response
     {
        // $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        // dump($repoArticle);
        // dump($id);

// On transmet à la méthode find() de la classe ArticleRepository l'id recupéré dans l'URL et transmit en argument de la fonction show($id) | $id = 3
// La méthode find() permet de selectionner en BDD un article par son ID
// on envoi sur le template les données selectionnées en BDD, c'est à dire les informations d'1 article en fonction l'id transmit dans l'URL


       //  $article = $repoArticle->find($id);
         dump($articleCr);
         return $this->render('blog/show.html.twig', [
             "articleTwig" => $articleCr
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
