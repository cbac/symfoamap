<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\AbstractLigne;
use App\Entity\Amap\LigneContrat;

/**
 * LigneContrat controller.
 */
class LigneContratController extends AbstractLigneContratController
{
    /**
     * Lists all Lignes entities.
     *
     * @Route("/lignecontrat/", name="lignecontrat_index")
     * @Route("/lignecontrat/list/", name="lignecontrat_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lignecontrats = $em->getRepository('App:Amap\LigneContrat')->findAll();
        return $this->renderList($lignecontrats, new LigneContrat());
    }
    /**
     * Finds and displays a LigneContrat entity.
     *
     *
     */
    public function showAction(Request $request, AbstractLigne $lignecontrat)
    {
        return $this->renderShow($lignecontrat);
        
    }
    /**
     * Creates a new LigneContrat entity.
     *
     * @Route("/lignecontrat/new", name="lignecontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newLigneAction(Request $request)
    {
        $lignecontrat = new LigneContrat();
        
        $form = $this->createForm('App\Form\LigneContratType', $lignecontrat);
        $form->handleRequest($request);
        return $this->renderNew($form, $lignecontrat);
    }

    /**
     * Displays a form to edit an existing LigneContrat entity.
     * @Route("/lignecontrat/{id}", name="lignecontrat_show", requirements={ "id": "\d+"})
     * @Route("/lignecontrat/{id}/edit", name="lignecontrat_edit", requirements={ "id": "\d+"})
     * @Method({"GET", "POST"})
     */
    public function editLigneAction(Request $request, AbstractLigne $ligne)
    {
        $editForm = $this->createForm('App\Form\LigneContratType', $ligne);
        
        $editForm->handleRequest($request);
        return $this->renderEdit($editForm, $ligne);
    }

    /**
     * Deletes a LigneContrat entity.
     *
     * @Route("/lignecontrat/{id}/delete", name="lignecontrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteLigneAction(Request $request, AbstractLigne $ligne)
    {
        $form = $this->createDeleteForm($ligne);
        $form->handleRequest($request);
        return $this->renderDelete($form, $ligne);
    }
}
