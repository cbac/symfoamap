<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\Produit;
use App\Form\ProduitType;

/**
 * Produit controller.
 */
class ProduitController extends Controller
{
    /**
     * Lists all Produit entities.
     *
     * @Route("/produit/", name="produit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('App:Amap\Produit')->findAll();

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));
    }

    /**
     * Finds and displays a Produit entity.
     *
     * @Route("/produit/{id}", name="produit_show", requirements={
	 *              "id": "\d+"
	 *     })
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Creates a new Produit entity.
     *
     * @Route("/produit/new", name="produit_new")
     * @Method({"GET", "POST"})
     */
    public function newProduitAction(Request $request) {
    	$produit = new Produit ();
    	$form = $this->createForm ( 'App\Form\ProduitType', $produit );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $produit );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', sprintf ( 'Produit %d ajouté', $produit->getId () ) );
    			
    		return $this->redirectToRoute ( 'produit_show', array (
    				'id' => $produit->getId ()
    		) );
    	}
    
    	return $this->render ( 'produit/new.html.twig', array (
    			'produit' => $produit,
    			'form' => $form->createView ()
    	) );
    }
    /**
     * Displays a form to edit an existing Produit entity.
     *
     * @Route("/produit/{id}/edit", name="produit_edit")
     * @Method({"GET", "POST"})
     */
    public function editProduitAction(Request $request, Produit $produit) {
    	$deleteForm = $this->createDeleteForm ( $produit );
    	$editForm = $this->createForm ( 'App\Form\ProduitType', $produit );
    
    	if ($editForm->isSubmitted () && $editForm->isValid ()) {
    	    $editForm->handleRequest ( $request );
    	    $em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $produit );
    		$em->flush ();
    			
    		return $this->redirectToRoute ( 'produit_show', array (
    				'id' => $produit->getId ()
    		) );
    	}
    
    	return $this->render ( 'produit/edit.html.twig', array (
    			'produit' => $produit,
    			'edit_form' => $editForm->createView (),
    			'delete_form' => $deleteForm->createView ()
    	) );
    }
    /**
     * Deletes a Produit entity.
     *
     * @Route("/produit/{id}/delete", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteProduitAction(Request $request, Produit $produit) {
    	$form = $this->createDeleteForm ( $produit );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->remove ( $produit );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', 'Produit '.$produit.' supprimé' );
    	}
    
    	return $this->redirectToRoute ( 'produit_index' );
    }
    /**
     * Creates a form to delete a Produit entity.
     *
     * @param Produit $produit
     *        	The Produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit) {
    	return $this->createFormBuilder ()->setAction ( $this->generateUrl ( 'produit_delete', array (
    			'id' => $produit->getId ()
    	) ) )->setMethod ( 'DELETE' )->getForm ();
    }
}
