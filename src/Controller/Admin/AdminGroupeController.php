<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminGroupeController extends AbstractController{

    //Récupération de tous les éléments de la table groupe
     /**
     * @Route("admin/groupes", name="admin_groupe_list")
     */
    public function AdminGroupeList(
        GroupeRepository $groupeRepository
    ){

        $groupes = $groupeRepository->findAll();

        return $this->render("admin/groupes.html.twig", ["groupes"=>$groupes]);
        
    }

    //Récupération d'un élément de la table groupe
    /**
     * @Route("admin/groupe/{id}", name="admin/groupe_show")
     */
    public function AdminGroupeShow($id,
    GroupeRepository $groupeRepository){

        $groupe = $groupeRepository->find($id);

        return $this->render("admin/groupe.html.twig", ['groupe' => $groupe]);

    }


    //Création d'un élément groupe
    /**
     *@Route("admin/create/groupe", name="admin_create_groupe")
     */
    public function adminCreateGroupe(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $groupe = new Groupe();

        $groupeForm = $this->createForm(GroupeType::class, $groupe);

        $groupeForm->handleRequest($request);

        if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
            $entityManagerInterface->persist($groupe);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Un groupe a été créé'
            );

            return $this->redirectToRoute('admin_groupe_list');
        }

        return $this->render('admin/groupeform.html.twig', ['groupeForm' => $groupeForm->createView()]);
    }


        //Modification d'un élément de la table groupe grâce à son id
    /**
     *@Route("admin/update/groupe/{id}", name="admin_update_groupe")
     */
    public function adminUpdateGroupe(
        $id,
        GroupeRepository $groupeRepository,
        Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
        EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
    ) {
        $groupe = $groupeRepository->find($id);

        // Création du formulaire
        $groupeForm = $this->createForm(GroupeType::class, $groupe);

        // Utilisation de handleRequest pour demander au formulaire de traiter les informations
        // rentrées dans le formulaire
        // Utilisation de request pour récupérer les informations rentrées dans le formualire
        $groupeForm->handleRequest($request);


        if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
            // persist prépare l'enregistrement ds la bdd analyse le changement à faire
            $entityManagerInterface->persist($groupe);
            $id = $groupeRepository->find($id);

            // flush enregistre dans la bdd
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Le groupe a bien été modifié !'
            );

            return $this->redirectToRoute('admin_groupe_list');
        }

        return $this->render('admin/groupeform.html.twig', ['groupeForm' => $groupeForm->createView()]);
    }

        //Suppression d'un élément de la table groupe grâce à son id

    /**
     * @Route("admin/delete/groupe/{id}", name="admin_delete_groupe")
     */
    public function adminDeleteGroupe(
        $id,
        GroupeRepository $groupeRepository,
        EntityManagerInterface $entityManagerInterface
    ) {

        $groupe = $groupeRepository->find($id);

        //remove supprime le groupe et flush enregistre ds la bdd
        $entityManagerInterface->remove($groupe);
        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'Votre groupe a bien été supprimé'
        );

        return $this->redirectToRoute('admin_groupe_list');
    }
}