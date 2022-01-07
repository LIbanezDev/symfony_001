<?php

namespace App\Controller;

use App\Repository\PetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'app_home', methods: 'GET')]
    public function index(PetRepository $petRepository, Request $request): Response
    {
        $pageNumber = $request->get('page', '1');
        $orderBy = $request->get('order', 'name');
        $pets = $petRepository->findPaginated((int)$pageNumber, $orderBy);
        $pages = ceil($petRepository->getCount() / 4);
        return $this->render('index.html.twig', [
            'pets' => $pets,
            'pages' => $pages
        ]);
    }
}