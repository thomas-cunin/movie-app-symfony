<?php

namespace App\Controller;

use DateTime;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Entity\Impression;
use App\Form\ImpressionType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MovieRepository $repo): Response
    {
        $movies = $repo->findAll();
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

     /**
     * @Route("/movie/{id}", name="oneMovie")
     */
    public function show(Movie $movie, Request $request, EntityManagerInterface $em): Response
    {
        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);
        dump($movie->getImpressions());
        if ($form->isSubmitted() && $form->isValid()) {
            $impression->setCreatedAt(new DateTime('now'));
            $impression->setMovie($movie);
            $em->persist($impression);
            $em->flush();
        }
        dump($movie);

        return $this->render('movie/one_movie.html.twig', [
            'movie' => $movie,
            'impressions' => $movie->getImpressions(),
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/movie/add", name="addMovie", priority=1)
     * @Route("/movie/{id}/edit", name="editMovie", priority=1)
     */
    public function edit(Movie $movie = null, EntityManagerInterface $em, Request $request): Response
    {
        $editMode = true;
        if (!$movie){
            $movie = new Movie();
            $editMode = false;
        }
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$editMode){
                $movie->setCreatedAt(new DateTime('now'));
            }
            $em->persist($movie);
            $em->flush();
            return $this->redirectToRoute('oneMovie', ['id' => $movie->getId()]);
        }


        return $this->render('movie/edit_movie.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
            'editMode' => $editMode,
        ]);
    }
    
    /**
    * @Route("/movie/{id}/delete", name="deleteMovie", priority=1)
    */
    public function remove(Movie $movie, EntityManagerInterface $em):Response
    {
        $em->remove($movie);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}

