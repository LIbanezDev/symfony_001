<?php

namespace App\Controller;

use App\Repository\PetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: 'GET')]
    public function index(PetRepository $petRepository, Request $request)
    {
        $pageNumber = $request->get('page') || 1;
        $pets = $petRepository->findPaginated($pageNumber);
        $pages = ceil($petRepository->getCount() / 4);
        return $this->render('index.html.twig', [
            'pets' => $pets,
            'pages' => $pages
        ]);
    }
}