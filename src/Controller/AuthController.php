<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    #[Route('/api/signup', name: 'auth_signup', methods: 'POST')]
    public function registration(
        UserPasswordHasherInterface $passwordHasher,
        Request                     $request,
        ManagerRegistry             $doctrine,
        ValidatorInterface          $validator,
        SluggerInterface            $slugger
    ): JsonResponse
    {
        $data = $request->request->all();
        $entityManager = $doctrine->getManager();
        if ($entityManager->getRepository('App:User')->findBy(['email' => $data['email']])) {
            return $this->json([
                'msg' => 'El email ya ha sido utilizado.'
            ], 400);
        };
        $user = new User();
        $plaintextPassword = $data['password'];
        $user->setPassword($plaintextPassword);
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setBornDate(\DateTime::createFromFormat('m-d-Y', $data['born_date']));

        $profile_image = $request->files->get('image');
        if ($profile_image !== null) {
            $originalFilename = pathinfo($profile_image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $profile_image->guessExtension();

            try {
                $profile_image->move(
                    $this->getParameter('users_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                return $this->json([
                    'msg' => 'Hubo un error subiendo la imagen de perfil'
                ]);
            }
            $user->setProfileImage($newFilename);
        }

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'errors' => $errors
            ]);
        }

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

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

        return $this->render('auth/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'auth_signup_view')]
    public function authSignupView(): Response
    {
        return $this->render('auth/signup.html.twig');
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
