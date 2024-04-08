<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentService extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function persistComment(Comment $comment, User $user, Product $product): void
    {
        $comment->setIsVerified(false)
            ->setUserComment($user)
            ->setProduct($product);

        $this->manager->persist($comment);
        $this->manager->flush();
        $this->addFlash('sucess', 'Votre Avis à bien été reçu et est en cours de verification, merci.');
    }
}
