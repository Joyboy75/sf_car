<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCarController extends AbstractController{

    //Récupération de tous les éléments de la table car
    /**
     * @Route("admin/cars", name="admin_car_list")
     */
    public function AdminCarList(
        CarRepository $carRepository
    ){

        $cars = $carRepository->findAll();

        return $this->render("admin/cars.html.twig", ["cars"=>$cars]);
        
    }

    //Récupération d'un élément de car
    /**
     * @Route("admin/car/{id}", name="admin_car_show")
     */
    public function AdminCarShow($id,
    CarRepository $carRepository){

        $car = $carRepository->find($id);

        return $this->render("admin/car.html.twig", ['car' => $car]);

    }


    //Création d'un élément dans car
    /**
     *@Route("admin/create/car", name="admin_create_car")
     */
    public function adminCreateCar(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $car = new Car();

        $carForm = $this->createForm(CarType::class, $car);

        $carForm->handleRequest($request);

        if ($carForm->isSubmitted() && $carForm->isValid()) {
            $entityManagerInterface->persist($car);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Un car a été créé'
            );

            return $this->redirectToRoute('admin_car_list');
        }

        return $this->render('admin/carform.html.twig', ['carForm' => $carForm->createView()]);
    }


    //Modification d'un élément de la table car grâce à son id
    /**
     *@Route("admin/update/car/{id}", name="admin_update_car")
     */
    public function adminUpdateCar(
        $id,
        CarRepository $carRepository,
        Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
        EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
    ) {
        $car = $carRepository->find($id);

        // Création du formulaire
        $carForm = $this->createForm(CarType::class, $car);

        // Utilisation de handleRequest pour demander au formulaire de traiter les informations
        // rentrées dans le formulaire
        // Utilisation de request pour récupérer les informations rentrées dans le formualire
        $carForm->handleRequest($request);


        if ($carForm->isSubmitted() && $carForm->isValid()) {
            // persist prépare l'enregistrement ds la bdd analyse le changement à faire
            $entityManagerInterface->persist($car);
            $id = $carRepository->find($id);

            // flush enregistre dans la bdd
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Le car a bien été modifié !'
            );

            return $this->redirectToRoute('admin_car_list');
        }

        return $this->render('admin/carform.html.twig', ['carForm' => $carForm->createView()]);
    }

        //Suppression d'un élément de la table car grâce à son id

    /**
     * @Route("admin/delete/car/{id}", name="admin_delete_car")
     */
    public function adminDeleteCar(
        $id,
        CarRepository $carRepository,
        EntityManagerInterface $entityManagerInterface
    ) {

        $car = $carRepository->find($id);

        //remove supprime le car et flush enregistre ds la bdd
        $entityManagerInterface->remove($car);
        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'Votre car a bien été supprimé'
        );

        return $this->redirectToRoute('admin_car_list');
    }
}