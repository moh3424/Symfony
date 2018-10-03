<?php
namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request;

use BoutiqueBundle\Entity\Membre;

use BoutiqueBundle\Form\MembreType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends Controller
{

     /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriptionAction(Request $request)
    {
    $passwordEncoder = $this -> get('security.password_encoder');
       $membre = new Membre;
       $membre -> setStatut(0);
       $membre -> setRole('ROLE_USER');
        $form = $this -> createForm(MembreType::class, $membre);

            // Je génère le formulaire (HTML - partie visuel)
            $formView = $form -> createView();

                // permet de récupérer les données du poste
            $form -> handleRequest($request);
            if ($form -> isSubmitted() && $form -> isValid() ){
                // On verra plus tard la validation


                $salt = substr(md5(time()), 0, 23);
                // time() : 1545223656
                // time() crypté en MD5 : 52154dsqf6s5564g46g6f5s65s565g654
                // ON conserve du 0 au 23 ème caractères : 52154dsqf6s5564g46g6f5s65

                $password = $passwordEncoder -> encodePassword($membre, $salt);
                $membre -> setPassword($password) -> setSalt($salt);
                // On mets dans l'objet $membre le nouveau password (crypté) et le salt (génèré aléatoirement) afin que ces deux valeurs soient enregistrée en BDD.
                
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
    public function connexionAction(Request $request)
    {
        $auth = $this-> get('security.authentication_utils');
        $error = $auth -> getLastAuthenticationError();
        $lastusername = $auth -> getLastUsername();
        $session = $request -> getSession();
      

        if(!empty($error)){
            $session -> getFlashBag() -> add('error', 'Identifiants incorrects');
        }
          $params = array (
            'lastusername' => $lastusername,
            'title' => 'connexion'
           
        );

        return $this->render('@Boutique/Membre/connexion.html.twig', $params);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */

    public function deconnexionAction(){}
    // Il faut juste la route existe pour que Symfony prenne le realais sur les fonctionnalités
  

}