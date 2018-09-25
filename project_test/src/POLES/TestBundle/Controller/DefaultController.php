<?php

namespace POLES\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * 
     * 
     * 
     * 
     */
    public function indexAction()
    {
        return $this->render('@POLESTest/Default/index.html.twig');
    }


    /**
     * @Route("/bonjour")
     */
    public function bonjourAction(){
        echo 'Bonjour';
        // return $this->render('@POLESTest/Default/index.html.twig');
    }

    
    /**
     * @Route("/bonjour2", name="bonjour2")
     */

    public function bonjour2Action(){
        return new Response('Voici la réponse');
        
    }


    /**
     * @Route("/hello/{prenom}")
     */

     public function helloAction($prenom){
        // return new Response ('Hello ' . $prenom);

        $response = new Response;
        $response -> setContent ('Hello ' . $prenom);
        return $response;
     }

     /**
      * @Route("/hola/{prenom}")
      */
     public function holaAction($prenom){
         $params = array(
            'prenom' => $prenom
         );
         return $this -> render('@POLESTest/Default/index2.html.twig', $params);;
     }

     /**
      * @Route("/hi/{prenom}")
      */

      public function hiAction($prenom, Request $request){ 
            $age = $request -> query -> get('age');  
            $params = array(
                'prenom' => $prenom,
                'age'    => $age
            );
            return $this->render('@POLESTest/Default/index3.html.twig', $params);
     }

    /**
    *@Route("/redirect")
    */

   public function redirectAction() { 
       $url = $this -> get('router') -> generate('bonjour2');

    return new RedirectResponse($url);   
}    

/**
 *@Route("/redirect2")
 */    public function redirect2Action () {

    return $this -> redirectToRoute('bonjour2');

}    

/**
 *@Route("/message")
 */

public function messageAction(Request $request){ 
    $session = $request -> getSession();   
    $session -> getFlashBag() -> add('test', 'voici le message ');
    $session -> getFlashBag() -> add('test', 'voici le message 2 ');
     $params = array ();

    return $this ->render('@POLESTest/Default/index4.html.twig', $params); 
   }

}/* fin class DefaultController */
