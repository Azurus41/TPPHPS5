<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PostController extends AbstractController
{
    // Mission 37 : freefakeapi.io n'existant plus, on utilise DummyJSON (https://dummyjson.com/docs)
    private const API_URL = 'https://dummyjson.com';

    private function simuler_traitement_long(): string
    {
        sleep(4);

        return "c'était long !!";
    }

    #[Route('/post', name: 'app_post')]
    public function index(HttpClientInterface $httpClient, CacheInterface $cache): Response
    {
        $texte = $cache->get('montexte', function (ItemInterface $item) {
            $item->expiresAfter(20);

            return $this->simuler_traitement_long();
        });

        // API non sécurisée : GET https://dummyjson.com/posts
        $posts = $cache->get('posts', function (ItemInterface $item) use ($httpClient) {
            $item->expiresAfter(20);

            $response = $httpClient->request('GET', self::API_URL.'/posts');

            return $response->toArray()['posts'];
        });

        return $this->render('post/index.html.twig', ['posts' => $posts, 'texte' => $texte]);
    }

    #[Route('/post_with_token', name: 'app_post_with_token')]
    public function indexWithToken(HttpClientInterface $httpClient, CacheInterface $cache): Response
    {
        $posts = $cache->get('posts_with_token', function (ItemInterface $item) use ($httpClient) {
            $item->expiresAfter(20);

            // 1. On récupère un token JWT (compte de test fourni par DummyJSON, voir .env)
            $response = $httpClient->request('POST', self::API_URL.'/auth/login', [
                'json' => [
                    'username' => $_ENV['DUMMYJSON_USER'],
                    'password' => $_ENV['DUMMYJSON_PASSWORD'],
                ],
            ]);
            $token = $response->toArray()['accessToken'];

            // 2. On l'utilise comme "bearer token" sur l'API sécurisée
            $response = $httpClient->request('GET', self::API_URL.'/auth/posts', [
                'auth_bearer' => $token,
            ]);

            return $response->toArray()['posts'];
        });

        return $this->render('post/index.html.twig', ['posts' => $posts, 'texte' => null]);
    }
}
