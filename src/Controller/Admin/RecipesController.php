<?php

namespace App\Controller\Admin;

use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/recipes', name: 'admin.recipes.')]
#[IsGranted('ROLE_ADMIN')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RecipesRepository $recipesRepository): Response
    {
        return $this->render('admin/recipes/index.html.twig', [
            'recipes' => $recipesRepository->findByDuration(25),
            'total' => $recipesRepository->getTotalDuration()
        ]);
    }

    #[Route('/new', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été ajoutée');
            return $this->redirectToRoute('admin.recipes.index');
        }

        return $this->render('admin/recipes/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-{slug}', name: 'show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS, 'slug' => Requirement::ASCII_SLUG])]
    public function show(Recipes $recipes): Response
    {
        return $this->render('admin/recipes/show.html.twig', [
            'recipe' => $recipes,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipes $recipes, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipesType::class, $recipes);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.recipes.index');
        }   

        return $this->render('admin/recipes/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $em, Recipes $recipe): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');

        return $this->redirectToRoute('admin.recipes.index');
    }
}
