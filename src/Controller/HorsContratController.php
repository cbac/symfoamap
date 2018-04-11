<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\HorsContrat;
use App\Entity\Amap\Personne;
use App\Form\HorsContratType;

/**
 * HorsContrat controller.
 */
class HorsContratController extends Controller
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
        
        return $this->renderList($horscontrats);
    }

    /**
     * Lists data constructed either in indexAction or in listAction
     */
    private function renderList($horscontrats)
    {
        $deleteforms = array();
        $editforms = array();
        foreach ($horscontrats as $contrat) {
            $deleteforms[] = $this->createDeleteForm($contrat)->createView();
            $editforms[] = $this->createEditForm($contrat)->createView();
        }
        return $this->render('contrat/list.html.twig', array(
            'titre' => 'Commandes Hors Contrat',
            'path_edit' => 'horscontrat_edit',
            'path_new' => 'horscontrat_new',
            'contrats' => $horscontrats,
            'deleteforms' => $deleteforms,
            'editforms' => $editforms
        ));
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
        
        $hcPersons = array();
        foreach ($hcontrats as $hcontrat) {
            $personne = $hcontrat->getPersonne();
            $hcPersons[$personne->__toString()] = array(
                'lignes' => $hcontrat->getLignes(),
                'id' => $personne->getid(),
                'cheque' => $personne->getCheque()
            );
        }
        ksort($hcPersons, SORT_STRING);
        
        return $this->render('contrat/listbyperson.html.twig', array(
            'titre' => 'Commandes Hors Contrat',
            'personnes' => $hcPersons
        ));
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
        
        $countProduits = array();
        $produitById = array();
        foreach ($horscontrats as $horscontrat) {
            $produit = $horscontrat->getProduit();
            $produitId = $produit->getId();
            if (array_key_exists($produitId, $countProduits)) {
                $countProduits[$produitId] += $horscontrat->getNombre();
            } else {
                $produitById[$produitId] = $produit;
                $countProduits[$produitId] = $horscontrat->getNombre();
            }
        }
        $horscontratProduits = array();
        foreach ($produitById as $id => $produit) {
            $horscontratProduits[$produit->__toString()] = $countProduits[$id];
        }
        ksort($horscontratProduits, SORT_STRING);
        return $this->render('contrat/listbyproduit.html.twig', array(
            'titre' => 'Commandes Hors Contrat',
            'produits' => $horscontratProduits
        ));
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
        $deleteForm = $this->createDeleteForm($contrat);
        $editForm = $this->createEditForm($contrat);
        return $this->render('contrat/show.html.twig', array(
            'titre' => 'Hors Contrat',
            'contrat' => $contrat,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
            'delete_lignes' => $this->createDeleteLignes($contrat),
            'edit_lignes' => $this->createEditLignes($contrat)
        ));
    }

    /**
     * Creates a new HorsContrat entity.
     *
     * @Route("/horscontrat/new", name="horscontrat_new")
     * @Method({"GET", "POST"})
     */
    public function newHorsContratAction(Request $request)
    {
        $horscontrat = new HorsContrat();
        
        $form = $this->createForm('App\Form\HorsContratType', $horscontrat);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($horscontrat);
            foreach ($horscontrat->getLignes() as $ligne) {
                $ligne->setContrat($horscontrat);
                $this->getDoctrine()
                    ->getManager()
                    ->persist($ligne);
                $this->addFlash('notice', sprintf('Ligne %d ajoutée', $ligne->getId()));
            }
            $em->flush();
            
            $this->addFlash('notice', sprintf('HorsContrat %d ajouté', $horscontrat->getId()));
            
            return $this->redirectToRoute('horscontrat_show', array(
                'id' => $horscontrat->getId()
            ));
        }
        
        return $this->render('contrat/new.html.twig', array(
            'contrat' => $horscontrat,
            'titre' => 'Hors Contrat',
            'form' => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/edit", name="horscontrat_edit")
     * @Method({"GET", "POST"})
     */
    public function editHorsContratAction(Request $request, HorsContrat $horscontrat)
    {
        $deleteForm = $this->createDeleteForm($horscontrat);
        $editForm = $this->createForm('App\Form\HorsContratType', $horscontrat);
        
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($horscontrat);
            foreach ($horscontrat->getLignes() as $ligne) {
                $ligne->setContrat($horscontrat);
                $this->getDoctrine()
                    ->getManager()
                    ->persist($ligne);
                $this->addFlash('notice', sprintf('Ligne %d ajoutée', $ligne->getId()));
            }
            $em->flush();
            
            return $this->redirectToRoute('horscontrat_show', array(
                'id' => $horscontrat->getId()
            ));
        }
        
        return $this->render('contrat/edit.html.twig', array(
            'titre' => 'Hors Contrat',
            'contrat' => $horscontrat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Delete a HorsContrat entity.
     *
     * @Route("/horscontrat/{id}/delete", name="horscontrat_delete")
     * @Method("DELETE")
     */
    public function deleteHorsContratAction(Request $request, HorsContrat $horscontrat)
    {
        $form = $this->createDeleteForm($horscontrat);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($horscontrat);
            $em->flush();
            
            $this->addFlash('notice', 'HorsContrat ' . $horscontrat . ' supprimé');
        }
        
        return $this->redirectToRoute('horscontrat_list');
    }

    /**
     * Creates a form to delete a HorsContrat entity.
     *
     * @param HorsContrat $horscontrat
     *            The HorsContrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HorsContrat $horscontrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('horscontrat_delete', array(
            'id' => $horscontrat->getId()
        )))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to edit a Personne HorsContrat entity.
     *
     * @param Personne $personne
     *            The HorsContrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HorsContrat $horscontrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('horscontrat_edit', array(
            'id' => $horscontrat->getId()
        )))
            ->setMethod('GET')
            ->getForm();
    }

    /**
     * Creates an array of form to delete each line in a Contract.
     *
     * @param HorsContrat $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    private function createDeleteLignes(HorsContrat $contrat)
    {
        $deleteforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $deleteforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl('lignehorscontrat_delete', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('DELETE')
                ->getForm()
                ->createView();
        }
        return $deleteforms;
    }

    /**
     * Creates an array of form to edit each line in a Contract.
     *
     * @param HorsContrat $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    private function createEditLignes(HorsContrat $contrat)
    {
        $editforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $editforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl('lignehorscontrat_edit', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('GET')
                ->getForm()
                ->createView();
        }
        return $editforms;
    }
}
