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
class LigneContratController extends Controller
{
    /**
     * Lists all Lignes entities.
     *
     * @Route("/lignecontrat/", name="ligne_index")
     * @Route("/lignecontrat/list/", name="ligne_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lignecontrats = $em->getRepository('App:Amap\LigneContrat')->findAll();
        return $this->renderList($lignecontrats, LigneContrat::path);
    }
    /**
     * Finds and displays a LigneContrat entity.
     *
     * @Route("/lignecontrat/{id}", name="lignecontrat_show", requirements={
     * "id": "\d+"
     * })
     * @Method("GET")
     */
    public function showAction(Request $request, AbstractLigne $lignecontrat)
    {
        return $this->renderShow($lignecontrat, LigneContrat::path);
        
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
        return $this->renderNew($request,$form,LigneContrat::path);
    }

    /**
     * Displays a form to edit an existing LigneContrat entity.
     *
     * @Route("/lignecontrat/{id}/edit", name="lignecontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editLigneAction(Request $request, AbstractLigne $ligne)
    {
        $editForm = $this->createForm('App\Form\LigneContratType', $ligne);
        
        $editForm->handleRequest($request);
        return $this->renderEdit($editForm, $ligne, LigneContrat::path);
    }

    /**
     * Deletes a LigneContrat entity.
     *
     * @Route("/lignecontrat/{id}/delete", name="lignecontrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteLigneContratAction(Request $request, LigneContrat $lignecontrat)
    {
        $form = $this->createDeleteForm($lignecontrat);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lignecontrat);
            $em->flush();
            
            $this->addFlash('notice', 'lignecontrat ' . $lignecontrat . ' supprimé');
        }
        
        return $this->redirectToRoute("contrat_list");
    }
}
