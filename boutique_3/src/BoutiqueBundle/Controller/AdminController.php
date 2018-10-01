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

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\integerType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Input type text
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // input type submit
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // input type checkbox

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
		
		// Obn récupère le builder de notre formulaire en lui passant l'objet qu'il représente
		$formBuilder = $this -> get('form.factory') -> createBuilder(FormType::class, $produit);

		// on définie les champs de notre formulaire
		$formBuilder			
			-> add('reference', TextType::class)
			-> add('categorie', TextType::class)
			-> add('titre', TextType::class)
			-> add('description', TextareaType::class)
			-> add('public', ChoiceType::class, array(
					'choices' => array(
						'Homme' => 'm',
						'Femme' => 'f'
					)
				))
			-> add('couleur', TextType::class)
			-> add('taille', TextType::class)
			-> add('photo', TextType::class)
			-> add('prix', TextType::class)
			-> add('stock', TextType::class)
			-> add('Ajouter', SubmitType::class);
		
		
		//On récupère le formulaire	
		$form = $formBuilder -> getForm();
		
		//On récupère la vue du formulaire
		$formView = $form -> createView();
		
		//permet de récupérer les données envoyées en post
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($produit);
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
		
		// ON construit le formulaire, en oubliant pas de passer l'objet en cours de modif
		$formBuilder = $this -> get('form.factory') -> createBuilder(FormType::class, $produit);

		$formBuilder			
			-> add('reference', TextType::class)
			-> add('categorie', TextType::class)
			-> add('titre', TextType::class)
			-> add('description', TextareaType::class)
			-> add('public', ChoiceType::class, array(
					'choices' => array(
						'Homme' => 'm',
						'Femme' => 'f'
					)
				))
			-> add('couleur', TextType::class)
			-> add('taille', TextType::class)
			-> add('photo', TextType::class)
			-> add('prix', TextType::class)
			-> add('stock', TextType::class)
			-> add('Modifier', SubmitType::class);
		
		
		$form = $formBuilder -> getForm();
		
		$formView = $form -> createView();
		
		$form -> handleRequest($request);
		
		if($form -> isSubmitted() && $form -> isValid()){
			
			$em = $this -> getDoctrine() -> getManager();
			$em -> persist($produit);
			$em -> flush();
			
			$session = $request -> getSession(); 
			//$session = $request -> get('session'); 
			
			$session -> getFlashBag() -> add('success', 'Le produit a bien été modifié');
			return $this -> redirectToRoute('show_produit'); 
		}
		
		$params = array(
			'produitForm' => $formView,
			'title' => 'Modifier le produit n°' . $id
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
	
	
}