<?php

namespace App\Controller\Front;

use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
     /**
     * @Route("/groupe", name="groupe")
     */
    public function index(): Response
    {
        return $this->render('groupe/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }

    /**
     * @Route("groupes", name="groupe_list")
     */
    public function groupeList(
        GroupeRepository $groupeRepository
    ){

        $groupes = $groupeRepository->findAll();

        return $this->render("front/groupes.html.twig", ["groupes"=>$groupes]);
        
    }

    /**
     * @Route("groupe/{id}", name="groupe_show")
     */
    public function groupeShow($id,
    GroupeRepository $groupeRepository){

        $groupe = $groupeRepository->find($id);

        return $this->render("front/groupe.html.twig", ['groupe' => $groupe]);

    }
}
