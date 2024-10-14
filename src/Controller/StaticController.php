<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class StaticController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home()
    {
        return $this->render('static/home.html.twig');
    }
}
