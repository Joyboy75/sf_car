<?php

namespace App\Controller\Front;

use App\Entity\Like;
use App\Entity\Dislike;
use App\Repository\CarRepository;
use App\Repository\LikeRepository;
use App\Repository\DislikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarController extends AbstractController
{
    /**
     * @Route("/car", name="car")
     */
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    /**
     * @Route("cars", name="car_list")
     */
    public function carList(
        CarRepository $carRepository
    ){

        $cars = $carRepository->findAll();

        return $this->render("front/cars.html.twig", ["cars"=>$cars]);
        
    }

    /**
     * @Route("car/{id}", name="car_show")
     */
    public function carShow($id,
    CarRepository $carRepository){

        $car = $carRepository->find($id);

        return $this->render("front/car.html.twig", ['car' => $car]);

    }

    /**
     * @Route("search", name="search")
     */
    public function frontSearch(Request $request, CarRepository $carRepository)
    {

        // Récupérer les données rentrées dans le formulaire
        $term = $request->query->get('term');
        // query correspond à l'outil qui permet de récupérer les données d'un formulaire en get
        // pour un formulaire en post on utilise request

        $cars = $carRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', ['cars' => $cars, 'term' => $term]);
    }

    /**
     * @Route("like/car/{id}", name="car_like")
     */
    public function likecar(
        $id,
        CarRepository $carRepository,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository
    ) {

        $car = $carRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                [
                    'code' => 403,
                    'message' => "Vous devez vous connecter"
                ],
                403
            );
        }

        if ($car->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy(
                [
                    'car' => $car,
                    'user' => $user
                ]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like supprimé",
                'likes' => $likeRepository->count(['car' => $car])
            ], 200);
        }

        if ($car->isDislikeByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);

            $entityManagerInterface->remove($dislike);

            $like = new Like();

            $like->setCar($car);
            $like->setUser($user);

            $entityManagerInterface->persist($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like ajouté et dislike supprimé",
                'likes' => $likeRepository->count(['car' => $car]),
                'dislikes' => $dislikeRepository->count(['car' => $car])
            ], 200);
        }


        $like = new Like();

        $like->setCar($car);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Like ajouté",
            'likes' => $likeRepository->count(['car' => $car])
        ], 200);
    }

    /**
     * @Route("dislike/car/{id}", name="car_dislike")
     */
    public function dislikecar(
        $id,
        CarRepository $carRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository,
        LikeRepository $likeRepository
    ) {

        $car = $carRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                [
                    'code' => 403,
                    'message' => "Vous devez vous connecter"
                ],
                403
            );
        }

        if ($car->isDislikeByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);

            $entityManagerInterface->remove($dislike);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Le dislike a été supprimé",
                'dislikes' => $dislikeRepository->count(['car' => $car])
            ], 200);
        }

        if ($car->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);

            $entityManagerInterface->remove($like);

            $dislike = new Dislike();
            $dislike->setCar($car);
            $dislike->setUser($user);

            $entityManagerInterface->persist($dislike);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "like supprimé et dislike ajouté",
                'dislikes' => $dislikeRepository->count(['car' => $car]),
                'likes' => $likeRepository->count(['car' => $car])
            ], 200);
        }


        $dislike = new Dislike();

        $dislike->setCar($car);
        $dislike->setUser($user);

        $entityManagerInterface->persist($dislike);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Dislike ajouté",
            'dislikes' => $dislikeRepository->count(['car' => $car])
        ], 200);
    }

    
}
