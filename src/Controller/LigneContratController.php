<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\Contrat;
use App\Entity\Amap\LigneContrat;
use App\Form\LigneContratType;

/**
 * LigneContrat controller.
 */
class LigneContratController extends Controller {
	
	/**
	 * Lists all Lignes entities.
	 * @Route("/lignecontrat/", name="ligne_index")
	 * @Route("/lignecontrat/list/", name="ligne_list")
	 * @Method("GET")
	 */
	public function listAction() {
		$em = $this->getDoctrine ()->getManager ();
		$lignecontrats = $em->getRepository ( 'App:Amap\LigneContrat' )->findAll ();
		return $this->renderList( $lignecontrats, 'lignecontrat/list.html.twig' );
	}
	/**
	 * Lists data constructed in listAction
	 */
	private function renderList($lignecontrats,$twig) {
		$deleteforms = array();
		foreach ($lignecontrats as $lignecontrat) {
			$deleteforms[] = $this->createDeleteForm($lignecontrat)->createView();
		}
		return $this->render($twig, array(
				'lignecontrats' => $lignecontrats,
				'deleteforms' => $deleteforms
		));
	}
	/**
	 * Finds and displays a LigneContrat entity.
	 *
	 * @Route("/lignecontrat/{id}", name="lignecontrat_show", requirements={
	 * "id": "\d+"
	 * })
	 * @Method("GET")
	 */
	public function showAction(Request $request, LigneContrat $lignecontrat) {
		$deleteForm = $this->createDeleteForm ( $lignecontrat );
		return $this->render ( 'lignecontrat/show.html.twig', array (
				'lignecontrat' => $lignecontrat,
				'delete_form' => $deleteForm->createView () 
		) );
	}
	/**
	 * Creates a new LigneContrat entity.
	 *
	 * @Route("/lignecontrat/new", name="lignecontrat_new")
	 * @Method({"GET", "POST"})
	 */
	public function newLigneContratAction(Request $request) {
		$lignecontrat = new LigneContrat ();
		
		$form = $this->createForm ( 'App\Form\LigneContratType', $lignecontrat );
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $lignecontrat );
			$em->flush ();
			
			$this->addFlash ( 'notice', sprintf ( 'lignecontrat %d ajouté', $lignecontrat->getId () ) );
			
			return $this->redirectToRoute ( 'contrat_show', array (
					'id' => $contrat->getId () 
			) );
		}
		
		return $this->render ( 'lignecontrat/new.html.twig', array (
				'lignecontrat' => $lignecontrat,
				'form' => $form->createView () 
		) );
	}
	/**
	 * Creates a new LigneContrat entity for a Contrat.
	 *
	 * @Route("/lignecontrat/add/{id}", name="lignecontrat_add") 
	 * 		requirements={ "id": "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function addLigneContratAction(Request $request, Contrat $contrat) {
		$lignecontrat = new LigneContrat ();
		$contrat->addLigne($lignecontrat);
	
		$form = $this->createForm ( 'App\Form\AddlignecontratType', $lignecontrat );
		$form->handleRequest ( $request );
	
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $lignecontrat );
			$em->flush ();
				
			$this->addFlash ( 'notice', sprintf ( 'lignecontrat %d ajouté', $contrat->getId () ) );
				
			return $this->redirectToRoute ( 'contrat_oneperson', array (
					'id' => $personne->getId ()
			) );
		}
	
		return $this->render ( 'lignecontrat/new.html.twig', array (
				'lignecontrat' => $lignecontrat,
				'form' => $form->createView ()
		) );
	}
	/**
	 * Displays a form to edit an existing LigneContrat entity.
	 *
	 * @Route("/lignecontrat/{id}/edit", name="lignecontrat_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editLigneContratAction(Request $request, LigneContrat $contrat) {

		$editForm = $this->createForm ( 'App\Form\LigneContratType', $lignecontrat );
		
		$editForm->handleRequest ( $request );
		
		if ($editForm->isSubmitted () && $editForm->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $lignecontrat );
			$em->flush ();
			
			return $this->redirectToRoute ( 'contrat_show', array (
					'id' => $contrat->getId () 
			) );
		}
		
		return $this->render ( 'lignecontrat/edit.html.twig', array (
				'lignecontrat' => $lignecontrat,
				'edit_form' => $editForm->createView ()
		) );
	}
	/**
	 * Deletes a LigneContrat entity.
	 *
	 * @Route("/lignecontrat/{id}/delete", name="lignecontrat_delete")
	 * @Method({"GET","DELETE"})
	 */
	public function deleteLigneContratAction(Request $request, LigneContrat $lignecontrat) {
		$form = $this->createDeleteForm ( $lignecontrat);
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid ()) {
			$em = $this->getDoctrine ()->getManager ();
			$em->remove ( $lignecontrat );
			$em->flush ();
			
			$this->addFlash ( 'notice', 'lignecontrat ' . $lignecontrat . ' supprimé' );
		}
		
		return $this->redirectToRoute ( "contrat_list" );
	}
	/**
	 * Creates a form to delete a LigneContrat entity.
	 *
	 * @param LigneContrat $lignecontrat
	 *        	The LigneContrat entity
	 *        	
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm(LigneContrat $lignecontrat) {
		return $this->createFormBuilder ()
			->setAction ( $this->generateUrl ( 'lignecontrat_delete', array ( 'id' => $lignecontrat->getId ()) ) )
			->setMethod ( 'DELETE' )->getForm ();
	}
	/**
	 * Creates a form to edit a LigneContrat entity.
	 *
	 * @param LigneContrat $lignecontrat
	 *        	The lignecontrat entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(LigneContrat $lignecontrat) {
	    return $this->createFormBuilder ()
	    ->setAction ( $this->generateUrl ( 'lignecontrat_edit', array ( 'id' => $lignecontrat->getId ()) ) )
	    ->setMethod ( 'GET' )->getForm ();
	}
}
