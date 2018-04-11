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
use App\Entity\Amap\LigneHorsContrat;

/**
 * Contrat controller.
 */
class LigneHorsContratController extends Controller {

    /**
     * Deletes a LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/{id}/delete", name="lignehorscontrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteLigneContratAction(Request $request, LigneHorsContrat $ligne) {
        $form = $this->createDeleteForm ( $ligne);
        $form->handleRequest ( $request );
        
        if ($form->isSubmitted () && $form->isValid ()) {
            $em = $this->getDoctrine ()->getManager ();
            $em->remove ( $lignet );
            $em->flush ();
            
            $this->addFlash ( 'notice', 'lignehorscontrat ' . $ligne . ' supprimé' );
        }
        
        return $this->redirectToRoute ( "horscontrat_list" );
    }
    /**
     * Creates a new LigneContrat entity.
     *
     * @Route("/lignecontrat/new", name="lignecontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newLigneHorsContratAction(Request $request) {
        $lignecontrat = new LigneHorsContrat ();
        
        $form = $this->createForm ( 'App\Form\LigneHorsContratType', $lignecontrat );
        $form->handleRequest ( $request );
        
        if ($form->isSubmitted () && $form->isValid ()) {
            $em = $this->getDoctrine ()->getManager ();
            $em->persist ( $lignecontrat );
            $em->flush ();
            
            $this->addFlash ( 'notice', sprintf ( 'lignehorscontrat %d ajouté', $lignecontrat->getId () ) );
            
            return $this->redirectToRoute ( 'horscontrat_show', array (
                'id' => $contrat->getId ()
            ) );
        }
        
        return $this->render ( 'lignehorscontrat/new.html.twig', array (
            'lignehorscontrat' => $lignecontrat,
            'form' => $form->createView ()
        ) );
    }
    /**
     * Displays a form to edit an existing LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/{id}/edit", name="lignehorscontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editLigneContratAction(Request $request, LigneHorsContrat $lignecontrat) {
        
        $editForm = $this->createForm ( 'App\Form\LigneHorsContratType', $lignecontrat );
        
        $editForm->handleRequest ( $request );
        
        if ($editForm->isSubmitted () && $editForm->isValid ()) {
            $em = $this->getDoctrine ()->getManager ();
            $em->persist ( $lignecontrat );
            $em->flush ();
            
            return $this->redirectToRoute ( 'lignehorscontrat_show', array (
                'id' => $lignecontrat->getId ()
            ) );
        }
        $deleteform = $this->createDeleteForm ( $lignecontrat);
        return $this->render ( 'lignecontrat/edit.html.twig', array (
            'titre' => 'Ligne Hors Contrat',
            'lignecontrat' => $lignecontrat,
            'edit_form' => $editForm->createView (),
            'delete_form' => $deleteform->createView()
        ) );
    }
    /**
     * Creates a form to delete a LigneContrat entity.
     *
     * @param LigneHorsContrat $lignecontrat
     *        	The LigneContrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LigneHorsContrat $lignecontrat) {
        return $this->createFormBuilder ()
        ->setAction ( $this->generateUrl ( 'lignehorscontrat_delete', array ( 'id' => $lignecontrat->getId ()) ) )
        ->setMethod ( 'DELETE' )->getForm ();
    }
    /**
     * Creates a form to edit a LigneContrat entity.
     *
     * @param LigneHorsContrat $lignecontrat
     *        	The lignecontrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(LigneHorsContrat $lignecontrat) {
        return $this->createFormBuilder ()
        ->setAction ( $this->generateUrl ( 'lignehorscontrat_edit', array ( 'id' => $lignecontrat->getId ()) ) )
        ->setMethod ( 'GET' )->getForm ();
    }
}
