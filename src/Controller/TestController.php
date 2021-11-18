<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TestController
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $response = $this->client->request('GET', 'http://external_api:8080/');
        return new JsonResponse($response->toArray());
    }
}
    