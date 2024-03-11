<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 31; $i++) {
            $product = new Product();

            $product->setName($faker->name())
                ->setPrice($faker->numberBetween(6, 55))
                ->setDescription($faker->paragraphs(5, true))
                ->setCategory($faker->paragraphs(1, true))
                ->setStock($faker->numberBetween(6, 12))
                ->setSize($faker->numberBetween(6, 25) . " cm");

            $manager->persist($product);
        }

        $manager->flush();
    }
}
