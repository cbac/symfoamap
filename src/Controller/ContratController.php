<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\Contrat;
use App\Entity\Amap\AbstractContrat;
use App\Entity\Amap\Personne;
use App\Entity\Amap\Produit;
use App\Form\ContratType;

/**
 * Contrat controller.
 */
class ContratController extends AbstractContratController
{

    /**
     * Lists all Contrat entities.
     *
     * @Route("/contrat/", name="contrat_index")
     * @Route("/contrat/list/", name="contrat_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contrats = $em->getRepository('App:Amap\Contrat')->findAll();
        return $this->renderList($contrats, new Contrat());
    }

    /**
     * Liste les contrats par utilisateur.
     *
     * @Route("/contrat/listbyperson/", name="contrat_byperson")
     * @Method("GET")
     */
    public function listbypersonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contrats = $em->getRepository('App:Amap\Contrat')->findAll();
        
        return $this->renderListByPerson($contrats, new Contrat());
    }

    /**
     * Liste les contrats par produit.
     *
     * @Route("/contrat/listbyproduit/", name="contrat_byproduit")
     * @Method("GET")
     */
    public function listbyproduitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ligneContrats = $em->getRepository('App:Amap\LigneContrat')->findAll();
        return $this->renderListByProduit($ligneContrats, new Contrat());
    }

    /**
     * Finds and displays a Contrat entity.
     *
     * @Route("/contrat/{id}", name="contrat_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, AbstractContrat $contrat)
    {
        return $this->renderShow($contrat);
    }

    /**
     * Creates a new Contrat entity.
     *
     * @Route("/contrat/new", name="contrat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contrat = new Contrat();
        
        $form = $this->createForm('App\Form\ContratType', $contrat);
        $form->handleRequest($request);
        return $this->renderNew($contrat, $form);
    }

    /**
     * Displays a form to edit an existing Contrat entity.
     *
     * @Route("/contrat/{id}/edit", name="contrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AbstractContrat $contrat)
    {
        $editForm = $this->createForm('\App\Form\ContratType', $contrat);
        $editForm->handleRequest($request);
        return $this->renderEdit($contrat, $editForm);
    }

    /**
     * Deletes a Contrat entity.
     *
     * @Route("/contrat/{id}/delete", name="contrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, AbstractContrat $contrat)
    {
        $form = $this->createDeleteForm($contrat);
        $form->handleRequest($request);
        return $this->renderDelete($contrat, $form);
    }
}
