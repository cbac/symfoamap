<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Amap\Contrat;
use AppBundle\Entity\Amap\Personne;
use AppBundle\Entity\Amap\Produit;
use AppBundle\Form\ContratType;

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
		$contrats = $em->getRepository ( 'AppBundle:Amap\Contrat' )->findAll ();
		return $this->renderList( $contrats, 'contrat/list.html.twig' );
	}
	/**
	 * Lists data constructed either in indexAction or in listNonValueAction
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
		$contrats = $em->getRepository ( 'AppBundle:Amap\Contrat' )->findAll ();
		
		$contractsByPersonId = array ();
		$personsById = array ();
		foreach ( $contrats as $contrat ) {
			$person = $contrat->getPersonne ();
			$personId = $person->getId ();
			if (! array_key_exists ( $personId, $contractsByPersonId )) {
				$personsById [$personId] = $person;
				$contractsByPersonId [$personId] = array ();
			}
			$contractsByPersonId [$personId] [] = $contrat;
		}
		
		$contractPersons = array ();
		foreach ( $personsById as $id => $person ) {
			$contractPersons [$person->__toString ()] = array (
					'id' => $id,
					'cheque' => $person->getCheque (),
					'contrats' => $contractsByPersonId [$id] 
			);
		}
		ksort ( $contractPersons, SORT_STRING );

		return $this->render ( 'contrat/listbyperson.html.twig', array (
				'personnes' => $contractPersons 
		) );
	}
	/**
	 * Liste les contrats par utilisateur.
	 *
	 * @Route("/contrat/listoneperson/{id}", name="contrat_oneperson", 
	 * 		requirements={ "id": "\d+" })
	 * @Method("GET")
	 */
	public function listOnepersonAction(Request $request, Personne $personne) {
		$em = $this->getDoctrine ()->getManager ();
		$contrats = $em->getRepository ( 'AppBundle:Amap\Contrat' )->
			findBy(array('personne'=>$personne->getId()));
			$deleteviews = array();
			$produitString= array();
			foreach ( $contrats as $contrat ) {
				$deleteForm = $this->createDeleteForm ( $contrat );
				$deleteviews[] =  $deleteForm->createView ();
				$produitString[] = $contrat->getProduit()->__toString();
			}
			
			$contractPerson = array (
					'tostring' => $personne->__toString (),
					'id' => $personne->getId(),
					'cheque' => $personne->getCheque (),
					'contrats' => $contrats,
					'produitString' => $produitString,
					'deleteforms' => $deleteviews
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
		$contrats = $em->getRepository ( 'AppBundle:Amap\Contrat' )->findAll ();
		
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
				'delete_form' => $deleteForm->createView () 
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
		
		$form = $this->createForm ( 'AppBundle\Form\ContratType', $contrat );
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
	 * @Route("/contrat/add/{id}", name="contrat_add") 
	 * 		requirements={ "id": "\d+" })
	 * @Method({"GET", "POST"})
	 */
	public function addContratAction(Request $request, Personne $personne) {
		$contrat = new Contrat ();
		$contrat->setPersonne($personne);
	
		$form = $this->createForm ( 'AppBundle\Form\AddContratType', $contrat );
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
	 * @Route("/contrat/{id}/edit", name="contrat_edit")
	 * @Method({"GET", "POST"})
	 */
	public function editContratAction(Request $request, Contrat $contrat) {

		$editForm = $this->createForm ( 'AppBundle\Form\ContratType', $contrat );
		
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
	 * Creates sorted infos for actions related to distribution : indexByProduit, indexByPersonne, indexDistrib
	 */
	private function getSortedInfos() {
		$em = $this->getDoctrine ()->getManager ();
		$this->contrats = $em->getRepository ( 'AppBundle:Amap\Contrat' )->findAll ();
		
		$this->alphaPersons = $em->getRepository ( 'AppBundle:Amap\Personne' )->findAll ();
		$this->alphaProduits = $em->getRepository ( 'AppBundle:Amap\Produit' )->findAll ();
		$this->personnes = $em->getRepository ( 'AppBundle:Amap\Personne' )->findById ();
		$this->produits = $em->getRepository ( 'AppBundle:Amap\Produit' )->findById ();
		
		$this->contratsByPersonId = array ();
		$curNb = 0;
		foreach ( $this->contrats as $contrat ) {
			$pid = $contrat->getPersonId ();
			if (! array_key_exists ( $pid, $this->contratsByPersonId )) {
				$this->contratsByPersonId [$pid] = array ();
			}
			$curNb = count ( $this->contratsByPersonId [$pid] );
			$this->contratsByPersonId [$pid] [$curNb] = $contrat;
		}
		$this->personByCount = array ();
		foreach ( $this->contratsByPersonId as $pid => $contrats ) {
			$count = 0;
			foreach ( $contrats as $contrat ) {
				$count += $contrat->getNombre ();
			}
			if (! array_key_exists ( $count, $personByCount )) {
				$this->personByCount [$count] = array ();
			}
			$curNb = count ( $this->personByCount [$count] );
			$this->personByCount [$count] [$curNb] = $pid;
		}
		ksort ( $this->personByCount );
	}
}
