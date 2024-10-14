<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hello')]
class HelloController
{
    public function __invoke(): Response
    {
        return new Response('Hello World!');
    }
}
