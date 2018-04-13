<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\Personne;
use App\Form\PersonneType;

/**
 * Personne controller.
 */
class PersonneController extends Controller
{
    /**
     * Lists all Personne entities.
     *
     * @Route("/personne/", name="personne_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $personnes = $em->getRepository('App:Amap\Personne')->findAll();
        $deleteforms = array();
        foreach ($personnes as $personne) {
            $deleteforms[] = $this->createDeleteForm($personne)->createView();
        }      
        return $this->render('personne/index.html.twig', array(
            'personnes' => $personnes,
            'deleteforms' => $deleteforms
        ));
    }

     /**
     * Finds and displays a Personne entity.
     *
     * @Route("/personne/{id}", name="personne_show", requirements={
	 *              "id": "\d+"
	 *     })
     * @Method("GET")
     */
    public function showAction(Personne $personne)
    {
        $deleteForm = $this->createDeleteForm($personne);

        return $this->render('personne/show.html.twig', array(
            'personne' => $personne,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Creates a new Personne entity.
     *
     * @Route("/personne/new", name="personne_new")
     * @Method({"GET", "POST"})
     */
    public function newPersonneAction(Request $request) {
    	$personne = new Personne ();
    	$form = $this->createForm ( 'App\Form\PersonneType', $personne );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $personne );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', sprintf ( 'Personne %d ajouté', $personne->getId () ) );
    			
    		return $this->redirectToRoute ( 'personne_show', array (
    				'id' => $personne->getId ()
    		) );
    	}
    
    	return $this->render ( 'personne/new.html.twig', array (
    			'personne' => $personne,
    			'form' => $form->createView ()
    	) );
    }
    /**
     * Displays a form to edit an existing Personne entity.
     *
     * @Route("/personne/{id}/edit", name="personne_edit")
     * @Method({"GET", "POST"})
     */
    public function editPersonneAction(Request $request, Personne $personne) {
    	$deleteForm = $this->createDeleteForm ( $personne );
    	$editForm = $this->createForm ( 'App\Form\PersonneType', $personne );
    	$editForm->handleRequest ( $request );
    
    	if ($editForm->isSubmitted () && $editForm->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->persist ( $personne );
    		$em->flush ();
    			
    		return $this->redirectToRoute ( 'personne_index', array (
    				'id' => $personne->getId ()
    		) );
    	}
    
    	return $this->render ( 'personne/edit.html.twig', array (
    			'personne' => $personne,
    			'edit_form' => $editForm->createView (),
    			'delete_form' => $deleteForm->createView ()
    	) );
    }
    /**
     * Deletes a Personne entity.
     *
     * @Route("/personne/{id}/delete", name="personne_delete")
     * @Method("DELETE")
     */
    public function deletePersonneAction(Request $request, Personne $personne) {
    	$form = $this->createDeleteForm ( $personne );
    	$form->handleRequest ( $request );
    
    	if ($form->isSubmitted () && $form->isValid ()) {
    		$em = $this->getDoctrine ()->getManager ();
    		$em->remove ( $personne );
    		$em->flush ();
    			
    		$this->addFlash ( 'notice', 'Personne '.$personne.' supprimé' );
    	}
    
    	return $this->redirectToRoute ( 'personne_index' );
    }
    /**
     * Creates a form to delete a Personne entity.
     *
     * @param Personne $personne
     *        	The Personne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Personne $personne) {
    	return $this->createFormBuilder ()->setAction ( $this->generateUrl ( 'personne_delete', array (
    			'id' => $personne->getId ()
    	) ) )->setMethod ( 'DELETE' )->getForm ();
    }
}
