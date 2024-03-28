<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entity
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $data = $this->entity->getRepository(Product::class)->findAll();
        $products = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
