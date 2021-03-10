<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for($i = 1; $i <= 10; $i++)
        {
            $articles = new Article; //ctlr + alt + i pr importer la classe (PHP namespace Resolver)

            $articles->setTitle("Titre de l'article n° $i")
                     ->setContent("<p> Contenu de l'article $i </p>")
                     ->setImgae("https://picsum.photos/600/400")
                     ->setCreatedAt(new \DateTime);

                     $manager->persist($articles);
                     

        }

        $manager->flush();
        
// Pour pouvoir insérer dans la table SQL 'Article', nous devons instancier un objet issu de cette classe
// L'entité 'Article' reflète la table SQL 'Article'
// Nous avons besoin de rensigner tout les setteurs et tout les objets $article afin de pouvoir générer les insertions en BDD
// On remplit les objets articles grace au setteurs
// En Symfony, nous avons besoin d'un manager qui permet de manipuler les lignes de la BDD (insertion, modification, suppression)
// persist() est une méthode issue de la classe ObjectManager qui permet de garder en mémoire les objets ârticle crées et préparer les requetes d'insertion (INSERT INTO)
// flush() est une méthode issue de la classe ObjectManager qui permet véritablement d'executer les insertions en BDD (similaire à execute() en PHP)
// une fois les fixtures réaliseés, il faut les charger en BDD grace à doctrine (ORM) par la commande : 
// php bin/console doctrine:fixtures:load


        
    }
}
