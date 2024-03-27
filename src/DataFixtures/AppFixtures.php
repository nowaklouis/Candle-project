<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use App\Entity\User;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        /***** admin fixture *****/
        $admin = new User();

        $admin->setEmail('admin@test.com')
            ->setUsername("Monsieur Admin")
            ->setAdress("7 rue de par là")
            ->setCity("Here")
            ->setIsVerified(true)
            ->setRoles(["ROLE_ADMIN"]);

        $passwordAdmin = $this->encoder->hashPassword($admin, 'passwordAdmin');
        $admin->setPassword($passwordAdmin);

        $manager->persist($admin);


        /***** user fixture *****/
        $user = new User();

        $user->setEmail('user@test.com')
            ->setUsername("Monsieur Client")
            ->setAdress("5 rue pas par là")
            ->setCity("NotHere")
            ->setIsVerified(true)
            ->setRoles(["ROLE_USER"]);

        $password = $this->encoder->hashPassword($user, 'password');
        $user->setPassword($password);

        $manager->persist($user);


        /***** category fixture *****/
        for ($i = 1; $i < 6; $i++) {
            $category = new Category();

            $category->setName($faker->word())
                ->setDescription($faker->words(15, true))
                ->setSlug($faker->slug());

            $manager->persist($category);


            /***** product fixture *****/
            for ($j = 1; $j < 16; $j++) {
                $product = new Product();

                $product->setName($faker->name())
                    ->setPrice($faker->numberBetween(6, 55))
                    ->setDescription($faker->paragraphs(5, true))
                    ->setCategory($category)
                    ->setStock($faker->numberBetween(6, 12))
                    ->setSize($faker->numberBetween(6, 25) . " cm")
                    ->setInShop($faker->randomElement([true, false]))
                    ->setProductAt($faker->dateTimeBetween('-6 month', 'now'))
                    ->setSlug($faker->slug())
                    ->setFile($faker->randomElement([
                        "candle-1.jpg",
                        "candle-2.jpg",
                        "candle-3.jpg",
                        "candle-4.jpg",
                        "candle-5.jpg",
                        "candle-6.webp",
                        "candle-7.webp",
                        "candle-8.jpg"
                    ]));

                $manager->persist($product);

                /***** comment fixture *****/
                for ($k = 1; $k < 4; $k++) {
                    $comment = new Comment();

                    $comment->setTitle($faker->words(3, true))
                        ->setContent($faker->paragraphs(3, true))
                        ->setUserComment($user)
                        ->setProduct($product);
                }
            }
        }
        $manager->flush();
    }
}
