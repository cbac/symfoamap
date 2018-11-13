<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\AbstractLigne;
use App\Entity\Amap\LigneHorsContrat;

/**
 * LigneContrat controller.
 */
class LigneHorsContratController extends AbstractLigneContratController {

    /**
     * Lists all Lignes entities.
     *
     * @Route("/lignehorscontrat/", name="lignehorscontrat_index")
     * @Route("/lignehorscontrat/list/", name="lignehorscontrat_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lignecontrats = $em->getRepository('App:Amap\LigneHorsContrat')->findAll();
        return $this->renderList($lignecontrats, new LigneHorsContrat());
    }
    /**
     * Finds and displays a LigneContrat entity.
     *
     * @Route("/lignehorscontrat/{id}", name="lignehorscontrat_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, AbstractLigne $lignecontrat)
    {
        return $this->renderShow($lignecontrat,'lignehorscontrat','lignehorscontrat');     
    }
    /**
     * Deletes a LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/{id}/delete", name="lignehorscontrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteLigneAction(Request $request, AbstractLigne $ligne) {
        $form = $this->createDeleteForm ($ligne);
        $form->handleRequest ($request);
        return $this->renderDelete($form, $ligne);
    }
    /**
     * Creates a new LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/new", name="lignehorscontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newLigneAction(Request $request) {
        $ligne = new LigneHorsContrat ();
        
        $form = $this->createForm ( 'App\Form\LigneHorsContratType', $ligne );
        $form->handleRequest ( $request );
        return $this->renderNew($form, $ligne);
    }
    /**
     * Displays a form to edit an existing LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/{id}/edit", name="lignehorscontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editLigneAction(Request $request, AbstractLigne $ligne) {
        
        $editForm = $this->createForm ( 'App\Form\LigneHorsContratType', $ligne );
        
        $editForm->handleRequest ( $request );
        return $this->renderEdit($editForm, $ligne);
    }
}
