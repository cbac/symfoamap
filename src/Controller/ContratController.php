<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\Contrat;
use App\Entity\Amap\Personne;
use App\Entity\Amap\Produit;
use App\Form\ContratType;

/**
 * Contrat controller.
 */
class ContratController extends Controller {
	/**
	 * Lists routes in contrat
	 *
	 * 
	 * @Method("GET")
	 */
	public function indexAction() {
		return $this->render ( 'contrat/index.html.twig' );
	}
	
	/**
	 * Lists all Contrat entities.
	 * @Route("/contrat/", name="contrat_index")
	 * @Route("/contrat/list/", name="contrat_list")
	 * @Method("GET")
	 */
	public function listAction() {
		$em = $this->getDoctrine ()->getManager ();
		$contrats = $em->getRepository ( 'App:Amap\Contrat' )->findAll ();
		return $this->renderList( $contrats, 'contrat/list.html.twig' );
	}
	/**
	 * Lists data constructed in listAction
	 */
	private function renderList($contrats,$twig) {
		$deleteforms = array();
		foreach ($contrats as $contrat) {
			$deleteforms[] = $this->createDeleteForm($contrat)->createView();
		}
		return $this->render($twig, array(
				'contrats' => $contrats,
				'deleteforms' => $deleteforms
		));
	}
	/**
	 * Liste les contrats par utilisateur.
	 *
	 * @Route("/contrat/listbyperson/", name="contrat_byperson")
	 * @Method("GET")
	 */
	public function listbypersonAction() {
		$em = $this->getDoctrine ()->getManager ();
		$contrats = $em->getRepository ( 'App:Amap\Contrat' )->findAll ();
		
		$cPersons = array ();
		foreach ( $contrats as $contrat ) {
		    $personne = $contrat->getPersonne();
		        $cPersons [$personne->__toString ()] = array (
		            'lignes' => $contrat->getLignes(),
		            'id' => $personne->getid(),
		            'cheque' => $personne->getCheque (),
		        );
		}

		ksort ( $cPersons, SORT_STRING );

		return $this->render ( 'contrat/listbyperson.html.twig', array (
				'personnes' => $cPersons 
		) );
	}
	/**
	 * Liste les contrats par utilisateur.
	 *
	 * @Route("/contrat/{id}/list", name="contrat_oneperson", 
	 * 		requirements={ "id": "\d+" })
	 * @Method("GET")
	 */
	public function listOnepersonAction(Request $request, Personne $personne) {
			$deleteviews = array();
			$editviews = array();
			$produitString= array();
			foreach ( $personne->getContrats() as $contrat ) {
				$deleteForm = $this->createDeleteForm ( $contrat );
				$deleteviews[] =  $deleteForm->createView ();
				$editForm = $this->createEditForm ( $contrat );
				$editviews[] =  $editForm->createView ();
				$produitString[] = $contrat->getProduit()->__toString();
			}
			
			$contractPerson = array (
					'tostring' => $personne->__toString (),
					'id' => $personne->getId(),
					'cheque' => $personne->getCheque (),
			         'contrats' => $personne->getContrats(),
					'produitString' => $produitString,
					'deleteforms' => $deleteviews,
			         'editforms' => $editviews
			);
		$res = $this->render ( 'contrat/listoneperson.html.twig', $contractPerson );
		return $res;
	}
	/**
	 * Liste les contrats par produit.
	 *
	 * @Route("/contrat/listbyproduit/", name="contrat_byproduit")
	 * @Method("GET")
	 */
	public function listbyproduitAction() {
		$em = $this->getDoctrine ()->getManager ();
		$contrats = $em->getRepository ( 'App:Amap\LigneContrat' )->findAll ();
		
		$countProduits = array ();
		$produitById = array ();
		foreach ( $contrats as $contrat ) {
			$produit = $contrat->getProduit ();
			$produitId = $produit->getId ();
			if (array_key_exists ( $produitId, $countProduits )) {
				$countProduits [$produitId] += $contrat->getNombre ();
			} else {
				$produitById [$produitId] = $produit;
				$countProduits [$produitId] = $contrat->getNombre ();
			}
		}
		$contratProduits = array ();
		foreach ( $produitById as $id => $produit ) {
			$contratProduits [$produit->__toString ()] = $countProduits [$id];
		}
		ksort ( $contratProduits, SORT_STRING );
		return $this->render ( 'contrat/listbyproduit.html.twig', array (
				'produits' => $contratProduits 
		) );
	}
	/**
	 * Finds and displays a Contrat entity.
	 *
	 * @Route("/contrat/{id}", name="contrat_show", requirements={
	 * "id": "\d+"
	 * })
	 * @Method("GET")
	 */
	public function showAction(Request $request, Contrat $contrat) {
		$deleteForm = $this->createDeleteForm ( $contrat );
		return $this->render ( 'contrat/show.html.twig', array (
				'contrat' => $contrat,
				'delete_form' => $deleteForm->createView (),
		        'delete_lignes' => $this->createDeleteLignes($contrat)
		) );
	}
	/**
	 * Creates a new Contrat entity.
	 *
	 * @Route("/contrat/new", name="contrat_new")
	 * @Method({"GET", "POST"})
	 */
	public function newContratAction(Request $request) {
		$contrat = new Contrat ();
		
		$form = $this->createForm ( 'App\Form\ContratType', $contrat );
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $contrat );
			foreach($contrat->getLignes() as $ligne)
			{
			    $ligne->setContrat($contrat);
			    $this->getDoctrine()->getManager()->persist($ligne);
			    $this->addFlash ( 'notice', sprintf ( 'Ligne %d ajoutée', $ligne->getId () ) );
			}

			$em->flush ();
			
			$this->addFlash ( 'notice', sprintf ( 'Contrat %d ajouté', $contrat->getId () ) );
			
			return $this->redirectToRoute ( 'contrat_show', array (
					'id' => $contrat->getId () 
			) );
		}
		
		return $this->render ( 'contrat/new.html.twig', array (
				'contrat' => $contrat,
				'form' => $form->createView () 
		) );
	}

	/**
	 * Displays a form to edit an existing Contrat entity.
	 *
	 * @Route("/contrat/{id}/edit", name="contrat_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editContratAction(Request $request, Contrat $contrat) {

		$editForm = $this->createForm ( '\App\Form\ContratType', $contrat );
		
		$editForm->handleRequest ( $request );
		
		if ($editForm->isSubmitted () && $editForm->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $contrat );
			foreach($contrat->getLignes() as $ligne)
			{
			    $ligne->setContrat($contrat);
			    $this->getDoctrine()->getManager()->persist($ligne);
			}
			$em->flush ();
			
			return $this->redirectToRoute ( 'contrat_show', array (
					'id' => $contrat->getId () 
			) );
		}
		
		return $this->render ( 'contrat/edit.html.twig', array (
				'contrat' => $contrat,
				'edit_form' => $editForm->createView ()
		) );
	}
	/**
	 * Deletes a Contrat entity.
	 *
	 * @Route("/contrat/{id}/delete", name="contrat_delete")
	 * @Method({"GET","DELETE"})
	 */
	public function deleteContratAction(Request $request, Contrat $contrat) {
		$form = $this->createDeleteForm ( $contrat);
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->remove ( $contrat );
			$em->flush ();
			
			$this->addFlash ( 'notice', 'Contrat ' . $contrat . ' supprimé' );
		}
		
		return $this->redirectToRoute ( "contrat_list" );
	}
	/**
	 * Creates a form to delete a Contrat entity.
	 *
	 * @param Contrat $contrat
	 *        	The Contrat entity
	 *        	
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(Contrat $contrat) {
		return $this->createFormBuilder ()
			->setAction ( $this->generateUrl ( 'contrat_delete', array ( 'id' => $contrat->getId ()) ) )
			->setMethod ( 'DELETE' )->getForm ();
	}
	/**
	 * Creates a form to edit a Contrat entity.
	 *
	 * @param Contrat $contrat
	 *        	The Contrat entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Contrat $contrat) {
	    return $this->createFormBuilder ()
	    ->setAction ( $this->generateUrl ( 'contrat_edit', array ( 'id' => $contrat->getId ()) ) )
	    ->setMethod ( 'GET' )->getForm ();
	}
	/**
	 * Creates an array of form to delete each line in a Contract.
	 *
	 * @param Contrat $contrat
	 *        	The Contrat entity
	 *
	 * @return array
	 */
	private function createDeleteLignes(Contrat $contrat) {
	    $deleteforms = array();
	    foreach ($contrat->getLignes() as $ligne) {
	        $deleteforms[] = $this->createFormBuilder ()
	    ->setAction ( $this->generateUrl ( 'lignecontrat_delete', array ( 'id' => $ligne->getId ()) ) )
	    ->setMethod ( 'DELETE' )->getForm ()->createView ();
	    }
	    return $deleteforms;
	}
}
