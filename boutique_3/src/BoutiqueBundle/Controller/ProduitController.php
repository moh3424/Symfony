<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProduitController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function indexAction()
    {

        $produits = array(
            0 => array(
                'id_produit' => 1,
                'reference' => '145465',
                'categorie' => 'pull',
                'description' => 'Super pull pour l\'hiver',
                'titre' => 'Pull noir',
                'couleur' => 'noir',
                'taille' => 'm',
                'public' => 'f',
                'photo' => 'jupe_robe noir_robe1.jpg',
                'prix' => '15.00',
                'stock' => '150'
            ),
            1 => array(
                'id_produit' => 2,
                'reference' => 'DFFD87',
                'categorie' => 'pantalon',
                'description' => 'Super pantalon pour l\'étè',
                'titre' => 'pantalon blanc',
                'couleur' => 'blanc',
                'taille' => 'm',
                'public' => 'm',
                'photo' => 'jupe_robe rose_robe2.jpg',
                'prix' => '45.00',
                'stock' => '50'
            ),
            2 => array(
                'id_produit' => 3,
                'reference' => 'FF5465',
                'categorie' => 'pantalon',
                'description' => 'Super pantalon pour l\'hiver',
                'titre' => 'pantalon noir',
                'couleur' => 'noir',
                'taille' => 'm',
                'public' => 'f',
                'photo' => 'pantalon_pantalon1_pantalon1.jpg',
                'prix' => '15.00',
                'stock' => '150'
            ),
        );

        
        $categories = array(
            0 => array(
                'categorie' => 'pull'
            ),
            1 => array(
                'categorie' => 'pantalon'
            )            
        );
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

     public function categorieAction()
     {
        $produits = array(
            0 => array(
                'id_produit' => 1,
                'reference' => '145465',
                'categorie' => 'pull',
                'description' => 'Super pull pour l\'hiver',
                'titre' => 'Pull noir',
                'couleur' => 'noir',
                'taille' => 'm',
                'public' => 'f',
                'photo' => 'jupe_robe noir_robe1.jpg',
                'prix' => '15.00',
                'stock' => '150'
            ),
            1 => array(
                'id_produit' => 2,
                'reference' => 'DFFD87',
                'categorie' => 'pantalon',
                'description' => 'Super pantalon pour l\'étè',
                'titre' => 'pantalon blanc',
                'couleur' => 'blanc',
                'taille' => 'm',
                'public' => 'm',
                'photo' => 'jupe_robe rose_robe2.jpg',
                'prix' => '45.00',
                'stock' => '50'
            )
        );

            $categories = array(
                0 => array(
                    'categorie' => 'pull'
                ),
                1 => array(
                    'categorie' => 'pantalon'
                )            
            );
            $params = array (
                'produits' => $produits,
                'categories' => $categories,
                'title' => 'Page Catégorie Pull'
            );

        return $this->render('@Boutique/Produit/index.html.twig', $params);
     }

     /**
      * @Route("/produit/{id}", name="produit")
      */

      public function produitAction(){
        //  $produit = $_GET['id_produit'];
      
          $produit = array(
                'id_produit' => 1,
                'reference' => '145465',
                'categorie' => 'pull',
                'description' => 'Super pull pour l\'hiver',
                'titre' => 'Pull noir',
                'couleur' => 'noir',
                'taille' => 'm',
                'public' => 'f',
                'photo' => 'jupe_robe rose_robe2.jpg',
                'prix' => '15.00',
                'stock' => '150'
          );

          $suggestions = array(
            0 => array(
                'id_produit' => 1,
                'reference' => '145465',
                'categorie' => 'pull',
                'description' => 'Super pull pour l\'hiver',
                'titre' => 'Pull noir',
                'couleur' => 'noir',
                'taille' => 'm',
                'public' => 'f',
                'photo' => 'jupe_robe noir_robe1.jpg',
                'prix' => '15.00',
                'stock' => '150'
            ),
            1 => array(
                'id_produit' => 2,
                'reference' => 'DFFD87',
                'categorie' => 'pantalon',
                'description' => 'Super pantalon pour l\'étè',
                'titre' => 'pantalon blanc',
                'couleur' => 'blanc',
                'taille' => 'm',
                'public' => 'm',
                'photo' => 'jupe_robe rose_robe2.jpg',
                'prix' => '45.00',
                'stock' => '50'
            )
        );

           
            
       
          
          $params = array (
            'produit' => $produit,
            'suggestions' => $suggestions,
            'title' => 'Produit :' .$produit['titre']
        );
         
             return $this->render('@Boutique/Produit/produit.html.twig',  $params);
         
      }


}
