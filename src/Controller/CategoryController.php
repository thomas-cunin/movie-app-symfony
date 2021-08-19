<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\MovieRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add", name="addCategory", priority=1)
     */
    public function add(Category $category= null, EntityManagerInterface $em, Request $request): Response
    {
        $editMode=true;
        if (!$category){
            $category = new Category();
            $editMode=false;
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('oneCategory', ['id'=>$category->getId()]);
        }
        
        return $this->render('category/add_category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/{id}", name="oneCategory")
     */
    public function show(Category $category): Response
    {
        $movies = $category->getMovies();
        dump($movies, $category);
        return $this->render('category/one_category.html.twig', [
            'movies' => $movies,
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/{id}/delete", name="deleteCategory")
     */
    public function remove(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}
