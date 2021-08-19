<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Movie;
use App\Entity\Category;
use App\Entity\Impression;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categorys = [
            'Horreur',
            'Comédie',
            'Drame',
            'Biographie'
        ];
        for ($i = 0; $i < count($categorys); $i++) {
            $category = new Category;
            $category->setName($categorys[$i]);
            $manager->persist($category);
            for ($j = 0; $j < rand(2, 6); $j++) {
                $movie = new Movie();
                $range = 5; // En années
                $createdAt = new \DateTime();
                $createdAt->setTimeStamp(rand($createdAt->getTimeStamp()-($range * 31536000), $createdAt->getTimeStamp()));
                $movie->setTitle('Un film de fou - Episode ' . $i.$j);
                $movie->setDescription('lalalalalalalalala');
                $movie->setPublishedAt($createdAt);
                $movie->setCreatedAt(new \Datetime());
                $movie->setDirector('Jean Michel de la croisière');
                $movie->setCategory($category);

                $manager->persist($movie);
                for ($k=0; $k < rand(0, 12); $k++) { 
                    $impression = new Impression;
                    $impression->setContent('Lorem ipsum dolorum faxum pythum is bestum');
                    $impression->setCreatedAt(new \DateTime());
                    $impression->setMovie($movie);
                    $manager->persist($impression);

                }
            }
        }

        $manager->flush();
    }
}
