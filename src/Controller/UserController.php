<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/api/admin', name: 'api_admin')]
    public function apiAdmin(): Response
    {
        return $this->json([
            'msg' => 'Estas autenticado y eres admin!, tu identificador: ' . $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/api/user', name: 'api_user')]
    public function apiUser(): Response
    {
        return $this->json($this->getUser());
    }

    #[Route('/api/user/{id}', name: 'api_user_id')]
    public function apiGetUserById(User $user): Response
    {
        return $this->json($user);
    }

    #[Route('/admin', name: 'admin_panel')]
    public function index(): Response
    {
        return $this->render('user/admin.html.twig');
    }

    #[Route('/profile', name: 'profile_page')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }

    #[Route('/profile/cats', name: 'profile_cats')]
    public function userCats(): Response
    {
        return $this->render('user/profile.html.twig');
    }
}
