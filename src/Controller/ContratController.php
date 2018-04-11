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
        return $this->renderList($contrats, 'contrat');
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
        
        return $this->renderListByPerson($contrats, Contrat::title);
    }

    /**
     * Liste les contrats par utilisateur.
     *
     * @Route("/contrat/{id}/list", name="contrat_oneperson",
     * 		requirements={ "id": "\d+" })
     * @Method("GET")
     */
    public function listOnepersonAction(Request $request, Personne $personne)
    {
        $deleteviews = array();
        $editviews = array();
        $produitString = array();
        foreach ($personne->getContrats() as $contrat) {
            $deleteForm = $this->createDeleteForm($contrat);
            $deleteviews[] = $deleteForm->createView();
            $editForm = $this->createEditForm($contrat);
            $editviews[] = $editForm->createView();
            $produitString[] = $contrat->getProduit()->__toString();
        }
        
        $contractPerson = array(
            'tostring' => $personne->__toString(),
            'id' => $personne->getId(),
            'cheque' => $personne->getCheque(),
            'contrats' => $personne->getContrats(),
            'produitString' => $produitString,
            'deleteforms' => $deleteviews,
            'editforms' => $editviews
        );
        $res = $this->render('contrat/listoneperson.html.twig', $contractPerson);
        return $res;
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
        $contrats = $em->getRepository('App:Amap\LigneContrat')->findAll();
        return $this->renderListByProduit($contrats,Contrat::title);
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
        return $this->renderShow($contrat, Contrat::path, Contrat::title);
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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            foreach ($contrat->getLignes() as $ligne) {
                $ligne->setContrat($contrat);
                $this->getDoctrine()
                    ->getManager()
                    ->persist($ligne);
                $this->addFlash('notice', sprintf('Ligne %d ajoutée', $ligne->getId()));
            }
            
            $em->flush();
            
            $this->addFlash('notice', sprintf('Contrat %d ajouté', $contrat->getId()));
            
            return $this->redirectToRoute(Contrat::path.'_show', array(
                'id' => $contrat->getId()
            ));
        }
        
        return $this->render('contrat/new.html.twig', array(
            'titre' => Contrat::title,
            'contrat' => $contrat,
            'form' => $form->createView()
        ));
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
        return $this->renderEdit($editForm, $contrat, Contrat::path, Contrat::title);
    }

    /**
     * Deletes a Contrat entity.
     *
     * @Route("/contrat/{id}/delete", name="contrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, AbstractContrat $contrat)
    {
        $form = $this->createDeleteForm($contrat, Contrat::path);
        $form->handleRequest($request);
        return $this->renderDelete($contrat, $form, Contrat::path, Contrat::title);
    }

    /**
     * Creates a form to delete a Contrat entity.
     *
     * @param Contrat $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
 /*   private function createDeleteForm(Contrat $contrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contrat_delete', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('DELETE')
            ->getForm();
    }
*/
    /**
     * Creates a form to edit a Contrat entity.
     *
     * @param Contrat $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
 /*   private function createEditForm(Contrat $contrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contrat_edit', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('GET')
            ->getForm();
    }
*/
    /**
     * Creates an array of form to delete each line in a Contract.
     *
     * @param Contrat $contrat
     *            The Contrat entity
     *            
     * @return array
     */
/*    private function createDeleteLignes(Contrat $contrat)
    {
        $deleteforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $deleteforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl('lignecontrat_delete', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('DELETE')
                ->getForm()
                ->createView();
        }
        return $deleteforms;
    }
    */
    /**
     * Creates an array of form to edit each line in a Contract.
     *
     * @param Contrat $contrat
     *            The Contrat entity
     *
     * @return array
     */
/*
    private function createEditLignes(Contrat $contrat)
    {
        $editforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $editforms[] = $this->createFormBuilder()
            ->setAction($this->generateUrl('lignecontrat_edit', array(
                'id' => $ligne->getId()
            )))
            ->setMethod('GET')
            ->getForm()
            ->createView();
        }
        return $editforms;
    }
    */
}
