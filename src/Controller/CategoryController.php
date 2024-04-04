<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'candle_category')]
    public function index(Category $category, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAllByCategory($category);

        return $this->render('category/index.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
