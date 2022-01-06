<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Repository\PetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/pet')]
class ApiPetController extends AbstractController
{

    #[Route('/', name: 'api_pet_all', methods: 'GET')]
    public function getAll(Request $request, PetRepository $petRepository): Response
    {
        return $this->json($petRepository->findAll(), 200, [], ['groups' => 'pet:no_owner']);
    }

    #[Route('', name: 'api_pet_create', methods: 'POST')]
    public function createPet(
        Request                $request,
        SerializerInterface    $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface     $validator
    ): Response
    {
        try {
            $data = $request->toArray();
            $owner = $entityManager->getRepository('App:User')->find((int)$data['owner_id']);
            if ($owner === null) return $this->json(['msg' => 'El dueño no existe'], 400);
            $clinic = $entityManager->getRepository('App:Clinic')->find((int)$data['clinic_id']);
            if ($clinic === null) return $this->json(['msg' => 'La clinica no existe'], 400);
            $pet = $serializer->deserialize($request->getContent(), Pet::class, 'json');
            $pet->setOwner($owner);
            $pet->setClinic($clinic);
            $errors = $validator->validate($pet);
            if (count($errors) > 0) return $this->json($errors, 400);
            $entityManager->persist($pet);
            $entityManager->flush();
            return $this->json($pet, 201, [], ['groups' => 'pet:no_owner']);
        } catch (\Exception $exception) {
            return $this->json(['msg' => 'Syntax Error'], 400);
        }
    }

    #[Route('/{id}', name: 'api_pet_one', methods: 'GET')]
    public function getOne(Pet $pet): Response
    {
        return $this->json($pet);
    }
}
