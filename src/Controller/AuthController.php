<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     */
    #[Route('/api/signup', name: 'auth_signup', methods: 'POST')]
    public function registration(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        ManagerRegistry $doctrine
    ): JsonResponse
    {
        $data = $request->request->all();
        $entityManager = $doctrine->getManager();

        $user = new User();
        $plaintextPassword = $data['password'];
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setBornDate(\DateTime::createFromFormat('m-d-Y', $data['born_date']));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'ok' => true,
            'msg' => 'Registro exitoso'
        ]);

    }

    #[Route('/login', name: 'auth_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
