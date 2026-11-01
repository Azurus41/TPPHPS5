<?php

namespace App\Controller;

use App\Taxe\CalculatorTaxe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HelloController extends AbstractController
{
    #[Route('/hello/world', name: 'app_hello_world')]
    public function show_hello_world(): Response
    {
        return $this->render('hello/hello_world.html.twig', [
            'controller_name' => 'HelloController',
        ]);
    }

    #[Route('/hello', name: 'app_hello')]
    public function hello(CalculatorTaxe $calculator): Response
    {
        $prixHT = 360;

        return $this->render('hello/hello.html.twig', [
            'prixHT' => $prixHT,
            'tva' => $calculator->calculerTVA($prixHT),
            'ttc' => $calculator->calculerTTC($prixHT),
        ]);
    }
}
