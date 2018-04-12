<?php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\HorsContrat;
use App\Form\HorsContratType;
use App\Entity\Amap\AbstractContrat;

/**
 * HorsContrat controller.
 */
class HorsContratController extends AbstractContratController
{

    /**
     * Lists all HorsContrat entities.
     *
     * @Route("/horscontrat/", name="horscontrat_index")
     * @Route("/horscontrat/list", name="horscontrat_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $horscontrats = $em->getRepository('App:Amap\HorsContrat')->findAll();
        
        return $this->renderList($horscontrats, new HorsContrat());
    }

    /**
     * Liste les contrats par utilisateur.
     *
     * @Route("/horscontrat/listbyperson/", name="horscontrat_byperson")
     * @Method("GET")
     */
    public function listbypersonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $hcontrats = $em->getRepository('App:Amap\HorsContrat')->findAll();
        return $this->renderListByPerson($hcontrats, new HorsContrat());
    }

    /**
     * Liste les horscontrats par produit.
     *
     * @Route("/horscontrat/listbyproduit/", name="horscontrat_byproduit")
     * @Method("GET")
     */
    public function listbyproduitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $horscontrats = $em->getRepository('App:Amap\LigneHorsContrat')->findAll();
        return $this->renderListByProduit($contrats, HorsContrat::title);
    }

    /**
     * Finds and displays a HorsContrat entity.
     *
     * @Route("/horscontrat/{id}", name="horscontrat_show", requirements={
     *              "id": "\d+"
     *     })
     * @Method("GET")
     */
    public function showAction(HorsContrat $contrat)
    {
        return $this->renderShow($contrat);
    }

    /**
     * Creates a new HorsContrat entity.
     *
     * @Route("/horscontrat/new", name="horscontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $horscontrat = new HorsContrat();
        
        $form = $this->createForm('App\Form\HorsContratType', $horscontrat);
        $form->handleRequest($request);
        return $this->renderNew($horscontrat, $form);
    }

    /**
     * Displays a form to edit an existing HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/edit", name="horscontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbstractContrat $contrat)
    {
        $editForm = $this->createForm('App\Form\HorsContratType', $contrat);
        $editForm->handleRequest($request);
        return $this->renderEdit($contrat, $editForm);
    }

    /**
     * Delete a HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/delete", name="horscontrat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AbstractContrat $contrat)
    {
        $form = $this->createDeleteForm($contrat);
        $form->handleRequest($request);       
        return $this->renderDelete($contrat, $form);
    }

}
