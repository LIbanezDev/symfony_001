<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker\Factory;

class LuckyController extends AbstractController
{

    #[Route('/lucky/number', name: 'app_lucky_number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
           'number' => $number
        ]);
    }

    #[Route('/api/lucky/string/{length?5}', name: 'app_lucky_string', requirements: ['length' => '\b[0-9]+\b'])]
    public function string(int $length): Response {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $this->render('lucky/string.html.twig', [
           'str' =>$randomString
        ]);
    }

    #[Route('/lucky/city', name: 'app_lucky_city', requirements: ['city' => '\b[a-zA-Z]+\b'])]
    public function city(): Response
    {
        $faker = Factory::create('es_ES');
        return $this->render('lucky/city.hmtl.twig', [
            'city' => $faker->city
        ]);
    }
}