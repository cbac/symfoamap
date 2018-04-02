<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\HorsContrat;
use App\Entity\Amap\Personne;
use App\Form\HorsContratType;


/**
 * HorsContrat controller.
 */
class HorsContratController extends Controller
{
    /**
     * HorsContrat actions choice
     *
     * @Route("/horscontrat/", name="horscontrat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('horscontrat/index.html.twig');
    }
    /**
     * Lists data constructed either in indexAction or in listAction
     */
    private function renderList($horscontrats) {
    	$deleteforms = array();
    	foreach ($horscontrats as $contrat) {
    		$deleteforms[] = $this->createDeleteForm($contrat)->createView();
    	}
    	return $this->render('horscontrat/list.html.twig', array(
    			'horscontrats' => $horscontrats,
    			'deleteforms' => $deleteforms
    	));
    }
    /**
     * Lists all HorsContrat entities.
     *
     * @Route("/horscontrat/list", name="horscontrat_list")
     * @Method("GET")
     */
    public function listAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$horscontrats = $em->getRepository('App:Amap\HorsContrat')->findAll();
    
    	return $this->renderList( $horscontrats);
    }
    /**
     * Liste les contrats par utilisateur.
     *
     * @Route("/horscontrat/listbyperson/", name="horscontrat_byperson")
     * @Method("GET")
     */
    public function listbypersonAction() {
    	$em = $this->getDoctrine ()->getManager ();
    	$personsById = $em->getRepository ( 'App:Amap\Personne' )->findAll ();
      
    	$hcPersons = array ();
    	foreach ( $personsById as $person ) {
    	    if($person->getHorsContrats()->count() > 0) {
        		$hcPersons [$person->__toString ()] = array (
    				'horscontrats' => $person->getHorsContrats(),
        		);
    	    }
    	}
    	ksort ( $hcPersons, SORT_STRING );
    
    	return $this->render ( 'horscontrat/listbyperson.html.twig', array (
    			'personnes' => $hcPersons
    	) );
    }
    /**
     * Liste les horscontrats par produit.
     *
     * @Route("/horscontrat/listbyproduit/", name="horscontrat_byproduit")
     * @Method("GET")
     */
    public function listbyproduitAction() {
    	$em = $this->getDoctrine ()->getManager ();
    	$horscontrats = $em->getRepository ( 'App:Amap\HorsContrat' )->findAll ();
    
    	$countProduits = array ();
    	$produitById = array ();
    	foreach ( $horscontrats as $horscontrat ) {
    		$produit = $horscontrat->getProduit ();
    		$produitId = $produit->getId ();
    		if (array_key_exists ( $produitId, $countProduits )) {
    			$countProduits [$produitId] += $horscontrat->getNombre ();
    		} else {
    			$produitById [$produitId] = $produit;
    			$countProduits [$produitId] = $horscontrat->getNombre ();
    		}
    	}
    	$horscontratProduits = array ();
    	foreach ( $produitById as $id => $produit ) {
    		$horscontratProduits [$produit->__toString ()] = $countProduits [$id];
    	}
    	ksort ( $horscontratProduits, SORT_STRING );
    	return $this->render ( 'horscontrat/listbyproduit.html.twig', array (
    			'produits' => $horscontratProduits
    	) );
    }
    /**
     * Finds and displays a HorsContrat entity.
     *
     * @Route("/horscontrat/{id}", name="horscontrat_show", requirements={
	 *              "id": "\d+"
	 *     })
     * @Method("GET")
     */
    public function showAction(HorsContrat $horscontrat)
    {
        $deleteForm = $this->createDeleteForm ($horscontrat);
        
        return $this->render('horscontrat/show.html.twig', array(
            'horscontrat' => $horscontrat,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Creates a new HorsContrat entity.
     *
     * @Route("/horscontrat/new", name="horscontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newHorsContratAction(Request $request) {
    	$horscontrat = new HorsContrat ();
    	
	   	$form = $this->createForm ( 'App\Form\HorsContratType', $horscontrat );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $horscontrat );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', sprintf ( 'HorsContrat %d ajouté', $horscontrat->getId () ) );
    			
    		return $this->redirectToRoute ( 'horscontrat_show', array (
    				'id' => $horscontrat->getId ()
    		) );
    	}
    
    	return $this->render ( 'horscontrat/new.html.twig', array (
    			'horscontrat' => $horscontrat,
    			'form' => $form->createView ()
    	) );
    }
    /**
     * Displays a form to edit an existing HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/edit", name="horscontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editHorsContratAction(Request $request, HorsContrat $horscontrat) {
    	$deleteForm = $this->createDeleteForm ( $horscontrat );
    	$editForm = $this->createForm ( 'App\Form\HorsContratType', $horscontrat );
    	
    	$editForm->handleRequest ( $request );
    
    	if ($editForm->isSubmitted () && $editForm->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $horscontrat );
    		$em->flush ();
    			
    		return $this->redirectToRoute ( 'horscontrat_show', array (
    				'id' => $horscontrat->getId ()
    		) );
    	}
    
    	return $this->render ( 'horscontrat/edit.html.twig', array (
    			'horscontrat' => $horscontrat,
    			'edit_form' => $editForm->createView (),
    			'delete_form' => $deleteForm->createView ()
    	) );
    }
    /**
     * Deletes a HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/delete", name="horscontrat_delete")
     * @Method("DELETE")
     */
    public function deleteHorsContratAction(Request $request, HorsContrat $horscontrat) {
    	$form = $this->createDeleteForm ( $horscontrat );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->remove ( $horscontrat );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', 'HorsContrat '.$horscontrat.' supprimé' );
    	}
    
    	return $this->redirectToRoute ( 'horscontrat_list' );
    }
    /**
     * Creates a form to delete a HorsContrat entity.
     *
     * @param HorsContrat $horscontrat
     *        	The HorsContrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HorsContrat $horscontrat) {
    	return $this->createFormBuilder ()->setAction ( $this->generateUrl ( 'horscontrat_delete', array (
    			'id' => $horscontrat->getId ()
    	) ) )->setMethod ( 'DELETE' )->getForm ();
    }
    /**
     * Creates a form to edit a Personne HorsContrat entity.
     *
     * @param Personne $personne
     *        	The HorsContrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HorsContrat $horscontrat) {
        return $this->createFormBuilder ()->setAction ( $this->generateUrl ( 'horscontrat_edit', array (
            'id' => $horscontrat->getId ()
        ) ) )->setMethod ( 'GET' )->getForm ();
    }
}
