<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Repository\PetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/pet')]
class ApiPetController extends AbstractController
{
    #[Route('/', name: 'api_pet_all', methods: 'GET')]
    public function getAll(PetRepository $petRepository): Response
    {
        return $this->json($petRepository->findAll());
    }

    #[Route('/{id}', name: 'api_pet_one', methods: 'GET')]
    public function getOne(Pet $pet): Response
    {
        return $this->json($pet);
    }
}
