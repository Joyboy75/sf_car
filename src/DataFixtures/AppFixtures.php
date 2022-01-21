<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Car;
use App\Entity\Brand;
use App\Entity\Groupe;
use App\Repository\BrandRepository;
use App\Repository\GroupeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    private $brandRepository;

    private $groupeRepository;

    public function __construct(BrandRepository $brandRepository, GroupeRepository $groupeRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->groupeRepository = $groupeRepository;
    }


    public function load(
        ObjectManager $manager
    ): void {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 6; $i++) {
            $brand = new Brand();

            $brand->setName($faker->word);
            $brand->setCountry($faker->country);

            $manager->persist($brand);

            $manager->flush();
        }

        for ($i = 0; $i < 6; $i++) {
            $groupe  = new Groupe();

            $groupe->setName($faker->lastName);
            $groupe->setCountry($faker->country);

            $manager->persist($groupe);

            $manager->flush();
        }

        for ($i = 0; $i < 10; $i++) {
            $car = new Car();

            $id_brand = rand(10, 15);
            $id_groupe = rand(9, 14);

            $brand = $this->brandRepository->find($id_brand);
            $groupe = $this->groupeRepository->find($id_groupe);

            $car->setName($faker->title);
            $car->setEngine($faker->name);
            $car->setYear($faker->year);
            $car->setDescription($faker->text);
            $car->setBrand($brand);
            $car->setGroupe($groupe);

            $manager->persist($car);
        }



        $manager->flush();
    }
}
