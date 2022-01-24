<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBrandController extends AbstractController{


    //Récupération de tous les éléments de la table brands 

    /**
     * @Route("admin/brands", name="admin_brand_list")
     */
    public function AdminBrandList(
        BrandRepository $brandRepository
    ){

        $brands = $brandRepository->findAll();

        return $this->render("admin/brands.html.twig", ["brands"=>$brands]);
        
    }


    // Récupération d'un élément de la page brand 
    /**
     * @Route("admin/brand/{id}", name="admin/brand_show")
     */
    public function AdminBrandShow($id,
    BrandRepository $brandRepository){

        $brand = $brandRepository->find($id);

        return $this->render("admin/brand.html.twig", ['brand' => $brand]);

    }




    //Création d'un élément  dans brand
    /**
     *@Route("admin/create/brand", name="admin_create_brand")
     */
    public function adminCreateBrand(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $brand = new Brand();

        $brandForm = $this->createForm(BrandType::class, $brand);

        $brandForm->handleRequest($request);

        if ($brandForm->isSubmitted() && $brandForm->isValid()) {
            $entityManagerInterface->persist($brand);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Un brand a été créé'
            );

            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brandform.html.twig', ['brandForm' => $brandForm->createView()]);
    }


    //Modification d'un élément brand grâce à son id 
    /**
     *@Route("admin/update/brand/{id}", name="admin_update_brand")
     */
    public function adminUpdateBrand(
        $id,
        BrandRepository $brandRepository,
        Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
        EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
    ) {
        $brand = $brandRepository->find($id);

        // Création du formulaire
        $brandForm = $this->createForm(BrandType::class, $brand);

        // Utilisation de handleRequest pour demander au formulaire de traiter les informations
        // rentrées dans le formulaire
        // Utilisation de request pour récupérer les informations rentrées dans le formualire
        $brandForm->handleRequest($request);


        if ($brandForm->isSubmitted() && $brandForm->isValid()) {
            // persist prépare l'enregistrement ds la bdd analyse le changement à faire
            $entityManagerInterface->persist($brand);
            $id = $brandRepository->find($id);

            // flush enregistre dans la bdd
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Le brand a bien été modifié !'
            );

            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brandform.html.twig', ['brandForm' => $brandForm->createView()]);
    }

    //Suppresion d'un élément grâce à son id
    /**
     * @Route("admin/delete/brand/{id}", name="admin_delete_brand")
     */
    public function adminDeleteBrand(
        $id,
        BrandRepository $brandRepository,
        EntityManagerInterface $entityManagerInterface
    ) {

        $brand = $brandRepository->find($id);

        //remove supprime le brand et flush enregistre ds la bdd
        $entityManagerInterface->remove($brand);
        $entityManagerInterface->flush();

        $this->addFlash(
            'notice',
            'Votre brand a bien été supprimé'
        );

        return $this->redirectToRoute('admin_brand_list');
    }
}