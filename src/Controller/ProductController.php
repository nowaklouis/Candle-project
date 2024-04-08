<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{slug}', name: 'candle_product')]
    public function index(
        Product $product,
        Request $request,
        CommentService $commentService
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $commentService->persistComment($comment, $this->getUser(), $product);

            return $this->redirectToRoute('candle_product', ['slug' => $product->getSlug()]);
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
