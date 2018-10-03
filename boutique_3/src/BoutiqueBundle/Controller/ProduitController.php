<?php

namespace BoutiqueBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BoutiqueBundle\Entity\Produit;

class ProduitController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction()
    {
        // Methode récupérer un repository :
        // $repository = $this -> getDoctrine() -> getRepository('BoutiqueBundle\Entity\Produit');
        $repository = $this -> getDoctrine() -> getRepository(Produit::class);
        $produits= $repository -> findAll();

    
        
        // echo'<pre>';
        // var_dump($produits);
        // echo'</pre>';

          // SELECT DISTINCT Categorie FROM produit
          $em = $this -> getDoctrine () -> getManager();
          $query =$em -> createQuery("SELECT DISTINCT p.categorie FROM BoutiqueBundle\Entity\Produit p");
          $categories = $query -> getResult();
        $params = array (
            'produits' => $produits,
            'categories' => $categories,
            'title' => 'Accueil'
        );

        return $this->render('@Boutique/Produit/index.html.twig', $params);
    }

    /**
     * @Route("/categorie/{categorie}", name="categorie")
     */

     public function categorieAction($categorie)
     {
        $repository = $this -> getDoctrine() -> getRepository(Produit::class);
        $produits = $repository -> findBy(['categorie' => $categorie]);

        // SELECT DISTINCT Categorie FROM produit
        $em = $this -> getDoctrine () -> getManager();
        $query = $em -> createQuery("SELECT DISTINCT p.categorie FROM BoutiqueBundle\Entity\Produit p");
        $categories = $query -> getResult();

            $params = array (
                'produits' => $produits,
                'categories' => $categories,
                'title' => 'Page Catégorie '. $categorie
            );

        return $this->render('@Boutique/Produit/index.html.twig', $params);
     }

     /**
      * @Route("/produit/{id}", name="produit")
      */

      public function produitAction($id){
        //  $produit = $_GET['id_produit'];
      
        //   Méthode 1:
        $repository = $this -> getDoctrine() -> getRepository(Produit::class);
        $produit= $repository -> find($id);

        //   Méthode 2:
        // $em = $this -> getDoctrine() -> getManager();
	    // $produit =$em -> find(Produit::class, $id);

        // On récupère les suggestions
      
    
        // $suggestions = $repository -> findBy (['categorie' => $produit -> getCategorie()]);

        // On récupère les suggestions avec queryBuilder, une requete créé en PHP :
        $em = $this -> getDoctrine() -> getManager();
        $query = $em -> createQueryBuilder();// Objet QueryBuilder

        $query
            -> select('p')
            -> from (Produit::class, 'p')
            ->where('p.categorie = :categorie')
            -> orderby('p.prix', 'DESC')
            -> setParameter('categorie', $produit -> getCategorie());

        // Ce query builder nous créé une requête qui s'apparenterai à ceci
        // SELECT * FROM produit WHERE categorie = :categorie ORDER BY prix DESC
        // bindParam(':categorie', $produit -> getCategorie())

        $suggestions = $query -> getQuery() -> getResult();
          
          $params = array (
            'produit' => $produit,
            'suggestions' => $suggestions,
            'title' => 'Produit :' .$produit -> getTitre()
        );
         
             return $this->render('@Boutique/Produit/produit.html.twig',  $params);
         
      }


}
