<?php
namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request;



use BoutiqueBundle\Entity\Membre;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Commande;
use BoutiqueBundle\Entity\DetailsCommande;

use BoutiqueBundle\Form\ProduitType;
use BoutiqueBundle\Form\MembreType;
use BoutiqueBundle\Form\CommandeType;

class AdminController extends Controller
{
	
	/**
	* @Route("/admin/", name="home_admin")
	*/
	public function homeAdminAction(){}
	
	
	/**
	* @Route("/admin/produit/show", name="show_produit")
	*
	* Route pour afficher un tableau avec tous les produits
	*/
	public function produitShowAction(){	
		
		// On récupère tous les produit (findAll())
		$repository = $this -> getDoctrine() -> getRepository(Produit::class);
		$produits = $repository -> findAll();
		
		//$params va nous permettre d'envoyer les données dans la vue
		$params = array(
			'produits' => $produits,
			'title' => 'Gestion des produits'
		);
		
		//On retourne la vue show_produit qui affiche les produits dans un tableau. 
		return $this -> render('@Boutique/admin/show_produit.html.twig', $params);	
	}
	
	
	
	
	/**
	* @Route("/admin/produit/add", name="add_produit")
	*
	* Route pour ajouter un produit (formulaire d'ajout)
	*/
	public function produitAddAction(Request $request){
		
		// Nos formulaires sont liés à une entité... Donc on instancie un objet lié à cette entité (produit)
		$produit = new Produit;
		
		// On récupère le builder de notre formulaire en lui passant l'objet qu'il représente
	
			$form = $this -> createForm(ProduitType::class, $produit);
		
		//On récupère la vue du formulaire
		$formView = $form -> createView();
		
		//permet de récupérer les données envoyées en post
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($produit);
			$produit -> chargementPhoto();
			$em -> flush();
			
			// On récupère un objet session permettant de manipuler la session. 
			$session = $request -> getSession();
			
			// On ajoute en session un message de validation
			$session -> getFlashBag() -> add('success', 'Le produit a bien été ajouté');
			
			return $this -> redirectToRoute('show_produit');
		}
		
		$params = array(
			'produitForm' => $formView,
			'title' => 'Ajouter produit'
		);
		
		return $this -> render('@Boutique/admin/form_produit.html.twig', $params);
	}
	
	
	/**
	* @Route("/admin/produit/update/{id}", name="update_produit")
	*
	* Route pour Modifier un produit 
	* Principe générale : On hydrate le formulaire du produit à modifier
	*/
	public function produitUpdateAction($id, Request $request){
		
		// On récupère le produit à modifier (grâce à l'id dans l'url)
		$repository = $this -> getDoctrine() -> getRepository(Produit::class);
		$produit = $repository -> find($id);
		
		$form =$this-> createForm(produitType::class, $produit);
		
		$formView = $form -> createView();
		
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($produit);
			$produit -> chargementPhoto();
			$em -> flush();
			
			$session = $request -> getSession(); 
			//$session = $request -> get('session'); 
			
			$session -> getFlashBag() -> add('success', 'Le produit a bien été modifié');
			return $this -> redirectToRoute('show_produit'); 
		}
		$params = array(
			'produitForm' => $formView,
			'title' => 'Modifier le produit n°' . $id,
			'photo' => $produit -> getPhoto(),
			

		);
		
		return $this -> render('@Boutique/admin/form_produit.html.twig', $params);	
	}
	
	
	
	/**
	* @Route("/admin/produit/delete/{id}", name="delete_produit")
	*
	* Route pour supprimer un produit de la BDD
	*/
	public function deleteProduitAction($id, Request $request){
		
		// On récupère le produit, via le manager... Parce qu'on en avoir besoin pour la suppression
		$em = $this -> getDoctrine()-> getManager();
		$produit = $em -> find(Produit::class, $id);
		
		$em -> remove($produit);
		$em -> flush();
		
		$session = $request -> getSession();
		$session -> getFlashBag() -> add('success', 'Le produit n°' . $id . ' a bien été supprimé');
		
		return $this -> redirectToRoute('show_produit');
	}
	

	/**
     * @Route("/admin/membre/show", name="show_membre")
     */

	public function showMembre(){
// On récupère tous les Membres (findAll())
		$repository = $this -> getDoctrine() -> getRepository(Membre::class);
		$membres = $repository -> findAll();


		$params = array (    
            'title' => 'Affichage des membres',
			'membres' => $membres
	);
		return $this -> render("@Boutique/admin/show_membre.html.twig", $params);
        
	}

	/**
	 * @Route("/admin/membre/update", name="update_membre")
	*/

	public function membreUpedateAction($id, Request $request){

		// On récupère le produit à modifier (grâce à l'id dans l'url)
		$repository = $this -> getDoctrine() -> getRepository(Membre::class);
		$membre = $repository -> find($id);
		
		$form =$this-> createForm(MembreType::class, $membre);
		
		$formView = $form -> createView();
		
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($membre);
			//$membre -> chargementPhoto();
			$em -> flush();
			
			$session = $request -> getSession(); 
			//$session = $request -> get('session'); 
			
			$session -> getFlashBag() -> add('success', 'Le membre a bien été modifié');
			return $this -> redirectToRoute('show_membre'); 
		}
		$params = array(
			'produitForm' => $formView,
			'title' => 'Modifier le membre n°' . $id
		);
		
		return $this -> render('@Boutique/admin/form_produit.html.twig', $params);	
	}

	/**
	* @Route("/admin/membre/delete/{id}", name="delete_membre")
	*
	*/
	public function deleteMembreAction($id, Request $request){
		
		// On récupère le produit, via le manager... Parce qu'on en avoir besoin pour la suppression
		$em = $this -> getDoctrine()-> getManager();
		$membre = $em -> find(Membre::class, $id);
		
		$em -> remove($membre);
		$em -> flush();
		
		$session = $request -> getSession();
		$session -> getFlashBag() -> add('success', 'Le membre n°' . $id . ' a bien été supprimé');
		
		return $this -> redirectToRoute('show_membre');
	}


	/**
	* @Route("/admin/membre/add", name="add_membre")
	*
	* Route pour ajouter un membre (formulaire d'ajout)
	*/
	public function membreAddAction(Request $request){
		
		// Nos formulaires sont liés à une entité... Donc on instancie un objet lié à cette entité (produit)
		$membre = new Membre;
		
		// On récupère le builder de notre formulaire en lui passant l'objet qu'il représente
	
			$form = $this -> createForm(MembreType::class, $membre);
		
		//On récupère la vue du formulaire
		$formView = $form -> createView();
		
		//permet de récupérer les données envoyées en post
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($membre);
			//$membre -> chargementPhoto();
			$em -> flush();
			
			// On récupère un objet session permettant de manipuler la session. 
			$session = $request -> getSession();
			
			// On ajoute en session un message de validation
			$session -> getFlashBag() -> add('success', 'Le membre a bien été ajouté');
			
			return $this -> redirectToRoute('show_membre');
		}
		
		$params = array(
			'membreForm' => $formView,
			'title' => 'Ajouter membre'
		);
		
		return $this -> render('@Boutique/admin/form_membre.html.twig', $params);
	}

    /**
     * @Route("admin/membre/profil/{id}", name="profil_membre")
     */
    public function profilMembreAction($id, Request $request)
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

                $request -> getSession() -> getFlashBag() -> add('success', 'la fiche de l\' utilisateur  n° :' . $id);
                return $this -> redirectToRoute('connexion');
            }



        $repository = $this -> getDoctrine() -> getRepository(Membre::class);
        $membre= $repository -> find($id);

       



        $params = array (    
            'title' => 'Le Profile des membres',
            'membreForm' => $formView,
            'membre' => $membre
            // 'suggestions' => $suggestions
           
        );

        var_dump($membre);

        return $this->render('@Boutique/Admin/profil_membre.html.twig' , $params);
    }






}