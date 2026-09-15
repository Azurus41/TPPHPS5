<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LuckyController extends AbstractController
{
    #[Route('/lucky', name: 'app_lucky')]
    public function index(): Response
    {
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
        ]);
    }

    #[Route('/lucky/number', name: 'app_lucky_number')]
    public function show_number(): Response
    {
        $number = random_int(0, 100);
        return new Response('Nombre tiré au sort : '.$number);
    }

    #[Route('/lucky/number_for_username', name: 'app_lucky_number_v2')]
    public function show_number_v2(Request $request): Response
    {
        $username = $request->query->get('username', '');
        $number = random_int(0, 100);
        return new Response('Nombre tiré au sort : '.$number.' pour '.$username);
    }

    #[Route('/lucky/number/v3', name: 'app_lucky_number_v3')]
    public function show_number_v3(Request $request): Response
    {
        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = random_int(0, 100);
        }
        return $this->render('lucky/number.html.twig', [
            'numbers' => $numbers,
        ]);
    }
}
