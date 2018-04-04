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
class LigneContratController extends Controller {
	/**
	 * Lists routes in 
	 *
	 * 
	 * @Method("GET")
	 */
	public function indexAction() {
		return $this->render ( 'contrat/index.html.twig' );
	}
	
	/**
	 * Lists all Lignes entities.
	 * @Route("/lignecontrat/", name="ligne_index")
	 * @Route("/lignecontrat/list/", name="ligne_list")
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
	 * @Route("/lignecontrat/listbyperson/", name="ligne_byperson")
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
		            'id' => $person->getid(),
		            'cheque' => $person->getCheque (),
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
	 * @Route("/lignecontrat/{id}/list", name="ligne_oneperson", 
	 * 		requirements={ "id": "\d+" })
	 * @Method("GET")
	 */
	public function listOnepersonAction(Request $request, Personne $personne) {
//		$em = $this->getDoctrine ()->getManager ();
//		$contrats = $em->getRepository ( 'App:Amap\Contrat' )->
//			findBy(array('personne'=>$personne->getId()));
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
	 * @Route("/lignecontrat/listbyproduit/", name="ligne_byproduit")
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
	 * @Route("/lignecontrat/{id}", name="lignecontrat_show", requirements={
	 * "id": "\d+"
	 * })
	 * @Method("GET")
	 */
	public function showAction(Request $request, Contrat $contrat) {
		$deleteForm = $this->createDeleteForm ( $contrat );
		return $this->render ( 'contrat/show.html.twig', array (
				'contrat' => $contrat,
				'delete_form' => $deleteForm->createView () 
		) );
	}
	/**
	 * Creates a new Contrat entity.
	 *
	 * @Route("/lignecontrat/new", name="lignecontrat_new")
	 * @Method({"GET", "POST"})
	 */
	public function newContratAction(Request $request) {
		$contrat = new Contrat ();
		
		$form = $this->createForm ( 'App\Form\ContratType', $contrat );
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $contrat );
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
	 * Creates a new Contrat entity for a user.
	 *
	 * @Route("/lignecontrat/add/{id}", name="lignecontrat_add") 
	 * 		requirements={ "id": "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function addContratAction(Request $request, Personne $personne) {
		$contrat = new Contrat ();
		$contrat->setPersonne($personne);
	
		$form = $this->createForm ( 'App\Form\AddContratType', $contrat );
		$form->handleRequest ( $request );
	
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $contrat );
			$em->flush ();
				
			$this->addFlash ( 'notice', sprintf ( 'Contrat %d ajouté', $contrat->getId () ) );
				
			return $this->redirectToRoute ( 'contrat_oneperson', array (
					'id' => $personne->getId ()
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
	 * @Route("/lignecontrat/{id}/edit", name="lignecontrat_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editContratAction(Request $request, Contrat $contrat) {

		$editForm = $this->createForm ( 'App\Form\ContratType', $contrat );
		
		$editForm->handleRequest ( $request );
		
		if ($editForm->isSubmitted () && $editForm->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $contrat );
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
	 * @Route("/lignecontrat/{id}/delete", name="lignecontrat_delete")
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
}
