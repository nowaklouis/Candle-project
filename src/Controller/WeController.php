<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WeController extends AbstractController
{
    #[Route('/nous', name: 'we')]
    public function index(): Response
    {
        return $this->render('we/index.html.twig');
    }
}
