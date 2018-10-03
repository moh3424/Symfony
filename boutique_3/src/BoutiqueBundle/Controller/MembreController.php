<?php

namespace BoutiqueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BoutiqueBundle\Entity\Membre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;






use BoutiqueBundle\Form\MembreType;


class MembreController extends Controller
{
    

    
    /**
     * 
     * @Route("membre/update/{id}")
     * 
     * 
     */
    public function membreUpdateAction($id){
        $em = $this -> getDoctrine() -> getManager();
        $membre = $em -> find(Membre::class, $id);

        $membre -> setPrenom('Romain');

        $em -> persist($membre);
        $em -> flush();

        return new Response("OK, le membre id:" . $id . " a été modifié");

        //tester : localhost:8000/membre/update/1
    }

     /**
     * 
     * @Route("membre/delete/{id}")
     * 
     * 
     */
    public function membreDeleteAction($id){
        $em = $this -> getDoctrine() -> getManager();
        $membre = $em -> find(Membre::class, $id);

        $em -> remove($membre);
        $em -> flush();

        return new Response("OK, le membre id:" . $id . " a été bien supprimé");

         //tester : localhost:8000/membre/delete/21
    }

    /**
     * @Route("profil/", name="profil")
     */

     public function profilAction(){



        $security = $this -> get('security.token_storage');
        $token = $security -> getToken();
        $user = $token -> getUser();


        // SELECT DISTINCT Categorie FROM produit
          
       

        // $params = array (
        //     'produits' => $produits,
        //     'categories' => $categories,
        //     'title' => 'Accueil'
        // );

      

        $params = array (
           
            'title' => 'Profil de ' . $user -> getUsername()
           
        );
        return  $this->render('@Boutique/Membre/profil.html.twig', $params);
     }


  
}