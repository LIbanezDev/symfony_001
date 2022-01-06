<?php

namespace App\Controller;

use App\Entity\Clinic;
use App\Repository\ClinicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/clinic')]
class ApiClinicController extends AbstractController
{
    #[Route('/', name: 'api_clinic_all', methods: 'GET')]
    public function getAll(ClinicRepository $clinicRepository): Response
    {
        return $this->json($clinicRepository->findAll(), 200, [], ['groups' => ['clinic:all']]);
    }

    #[Route('/{id}', name: 'api_clinic_byId', methods: 'GET')]
    public function getOne(Clinic $clinic): Response
    {
        return $this->json($clinic, 200, [], ['groups' => ['clinic:all']]);
    }

    #[Route('', name: 'api_clinic_create', methods: 'POST')]
    public function createClinic(
        Request                $request,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        EntityManagerInterface $entityManager
    ): Response
    {
        try {
            $clinic = $serializer->deserialize($request->getContent(), Clinic::class, 'json');
            $errors = $validator->validate($clinic);
            if (count($errors) > 0) return $this->json($errors, 400);
            $entityManager->persist($clinic);
            $entityManager->flush();
            return $this->json(['msg' => 'Clinica creada exitosamente']);
        } catch (\Exception $exception) {
            return $this->json(['msg' => $exception->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'api_clinic_update', requirements: ['id' => '^[1-9]\d*$'], methods: 'PUT')]
    public function updateClinic(
        Request                $request,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        EntityManagerInterface $entityManager,
        int                    $id
    ): Response
    {
        try {
            $clinic = $entityManager->getRepository('App:Clinic')->find($id);
            if (!$clinic) {
                throw $this->createNotFoundException(
                    'No existe la clinica con id ' . $id
                );
            }
            return $this->json('En constru');
        } catch (\Exception $exception) {
            return $this->json(['msg' => $exception->getMessage()], 400);
        }
    }

    #[Route('/{id}', name: 'api_clinic_remove', methods: 'DELETE')]
    public function removeClinic(
        Clinic                 $clinic,
        EntityManagerInterface $entityManager
    ): Response
    {
        $entityManager->remove($clinic);
        $entityManager->flush();
        return $this->json(['msg' => 'Clinica eliminada.']);
    }


}
