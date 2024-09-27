<?php

namespace App\Controller\API;

use App\Entity\Recipes;
use App\Repository\RecipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{
    #[Route("/api/recipes")]
    public function index (RecipesRepository $recipesRepository, Request $request, SerializerInterface $serializer) 
    {
        $recipes = $recipesRepository->paginateRecipes($request->query->getInt('page', 1));
        // dd($serializer->serialize($recipes, 'yaml', [
        //     'groups' => 'recipes.index'
        // ]));
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index']
        ]);
    }

    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show (Recipes $recipes) 
    {
        return $this->json($recipes, 200, [], [
            'groups' => ['recipes.index','recipes.show']
        ]);
    }
}