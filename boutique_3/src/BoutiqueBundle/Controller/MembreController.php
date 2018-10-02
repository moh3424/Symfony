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
     * @Route("/connexion/", name="connexion")
     */
    public function connexionAction()
    {
          $params = array (
            'title' => 'connexion'
           
        );

        return $this->render('@Boutique/Membre/connexion.html.twig', $params);
    }
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

  
}