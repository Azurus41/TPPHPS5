<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PostController extends AbstractController
{
    // Mission 37 : API freefakeapi.io (le domaine est actuellement hors service, voir fallback ci-dessous)
    private const API_URL = 'https://freefakeapi.io/api';

    private function simuler_traitement_long(): string
    {
        sleep(4);

        return "c'était long !!";
    }

    /**
     * Posts d'exemple, utilisés si l'API distante est injoignable.
     */
    private function postsDeSecours(): array
    {
        return [
            ['id' => 1, 'title' => 'Mes conseils pour une pâte à crêpes', 'content' => "Laissez reposer la pâte une heure avant la cuisson."],
            ['id' => 2, 'title' => 'Tomates : bien les choisir', 'content' => 'Préférez des tomates fermes et parfumées.'],
            ['id' => 3, 'title' => 'Le secret d\'un bon risotto', 'content' => 'Ajoutez le bouillon louche par louche.'],
        ];
    }

    private function recupererPosts(HttpClientInterface $httpClient, LoggerInterface $logger, array $options = []): array
    {
        try {
            $response = $httpClient->request('GET', self::API_URL.'/posts', $options + ['timeout' => 5]);
            $posts = $response->toArray();

            // API Platform renvoie parfois les éléments dans "member" / "hydra:member"
            return $posts['member'] ?? $posts['hydra:member'] ?? $posts;
        } catch (\Throwable $e) {
            $logger->error('freefakeapi.io injoignable : '.$e->getMessage());

            return $this->postsDeSecours();
        }
    }

    #[Route('/post', name: 'app_post')]
    public function index(HttpClientInterface $httpClient, CacheInterface $cache, LoggerInterface $logger): Response
    {
        $texte = $cache->get('montexte', function (ItemInterface $item) {
            $item->expiresAfter(20);

            return $this->simuler_traitement_long();
        });

        $posts = $cache->get('posts', function (ItemInterface $item) use ($httpClient, $logger) {
            $item->expiresAfter(20);

            return $this->recupererPosts($httpClient, $logger);
        });

        return $this->render('post/index.html.twig', ['posts' => $posts, 'texte' => $texte]);
    }

    #[Route('/post_with_token', name: 'app_post_with_token')]
    public function indexWithToken(HttpClientInterface $httpClient, CacheInterface $cache, LoggerInterface $logger): Response
    {
        $posts = $cache->get('posts_with_token', function (ItemInterface $item) use ($httpClient, $logger) {
            $item->expiresAfter(20);

            try {
                $response = $httpClient->request('GET', self::API_URL.'/login', [
                    // identifiants d'un utilisateur de freefakeapi.io (voir .env)
                    'auth_basic' => [$_ENV['FREEFAKEAPI_USER'] ?? '', $_ENV['FREEFAKEAPI_PASSWORD'] ?? ''],
                    'timeout' => 5,
                ]);
                $token = $response->toArray()['token'];
            } catch (\Throwable $e) {
                $logger->error('Token freefakeapi.io non obtenu : '.$e->getMessage());

                return $this->postsDeSecours();
            }

            return $this->recupererPosts($httpClient, $logger, ['auth_bearer' => $token]);
        });

        return $this->render('post/index.html.twig', ['posts' => $posts, 'texte' => null]);
    }
}
