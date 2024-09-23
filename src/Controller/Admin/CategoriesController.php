<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/categories', name: 'admin.categories.')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAllWithCount()
        ]);
    }


    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été ajoutée');
            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('admin/categories/create.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Categories $categories, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin.categories.index');
        }   

        return $this->render('admin/categories/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $em, Categories $categories): Response
    {
        $em->remove($categories);
        $em->flush();
        $this->addFlash('success', 'La catégorie a bien été supprimée');

        return $this->redirectToRoute('admin.categories.index');
    }
}
