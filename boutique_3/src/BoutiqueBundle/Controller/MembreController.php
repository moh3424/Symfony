<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BoutiqueBundle\Entity\Membre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;// input type text
use Symfony\Component\Form\Extension\Core\Type\PasswordType; // input type password
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // input type Integer
use Symfony\Component\Form\Extension\Core\Type\EmailType; // input type email
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // input type checkbox
use Symfony\Component\Form\Extension\Core\Type\SubmitType;  // input type submit


class MembreController extends Controller
{
       /**
     * @Route("/inscription/", name="inscription")
     */
    public function inscriptionAction(Request $request)
    {

       $membre = new Membre;
       $formBuilder = $this -> get('form.factory') -> createBuilder(FormType::class, $membre);

       // $formBuilder = $this -> creatFormBuilder($membre);

       $formBuilder 
            -> add ('pseudo', TextType::class)
            -> add ('prenom', TextType::class)
            -> add ('mdp', PasswordType::class)
            -> add ('nom', TextType::class)
            -> add ('email', EmailType::class)
            -> add ('civilite', ChoiceType::class, array (
                'choices' =>  array (
                    'homme' => 'h',
                    'femme'=> 'f'
                )
            ))
            -> add ('ville', TextType::class)
            -> add ('codePostal', IntegerType::class)
            -> add ('adresse', TextType::class)
            -> add ('inscription', SubmitType::class);


            // Je récupère le formulaire :
            $form = $formBuilder -> getForm();

            // Je génère le formulaire (HTML - partie visuel)
            $formView = $form -> createView();

                // permet de récupérer les données du poste
            $form -> handleRequest($request);
            if ($form -> isSubmitted() && $form -> isValid() ){
                // On verra plus tard la validation
                
                $em = $this -> getDoctrine() -> getManager();
                $em -> persist($membre);
                $em -> flush();

                $request -> getSession() -> getFlashBag() -> add('success', 'Félicitation, vous êtes inscrit !');
                return $this -> redirectToRoute('connexion');
            }



        $repository = $this -> getDoctrine() -> getRepository(Membre::class);
        $membres= $repository -> findAll();
        $params = array (    
            'title' => 'Inscription',
            'membreForm' => $formView,
            'membres' => $membres
        );

        return $this->render('@Boutique/Membre/inscription.html.twig', $params);
    }

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