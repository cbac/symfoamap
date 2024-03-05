<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\AbstractContrat;

/**
 * Contrat controller.
 */
abstract class AbstractContratController extends Controller
{

    abstract public function listAction();

    /**
     * Render data constructed for listAction
     * used in derived classes
     */
    protected function renderList($contrats, AbstractContrat $ctype)
    {
        $deleteforms = array();
        $editforms = array();
        $showforms = array();
        $newForm = null;
        foreach ($contrats as $contrat) {
            $deleteforms[] = $this->createDeleteForm($contrat)->createView();
            $editforms[] = $this->createEditForm($contrat)->createView();
            $showforms[] = $this->createShowForm($contrat)->createView();
        }
        $newForm = $this->createNewForm($ctype)->createView();
        
        return $this->render('contrat/list.html.twig', array(
            'titre' => $ctype::title,
            'path_edit' => $ctype::path . '_edit',
            'path_new' => $ctype::path . '_new',
            'contrats' => $contrats,
            'deleteforms' => $deleteforms,
            'showforms' => $showforms,
            'editforms' => $editforms,
            'new_form' => $newForm
        ));
    }

    /**
     * Render data constructed for lisbypersonAction.
     */
    protected function renderListByPerson($contrats, AbstractContrat $ctype)
    {
        $cPersons = array();
        foreach ($contrats as $contrat) {
            $personne = $contrat->getPersonne();
            $cPersons[$personne->__toString()] = array(
                'lignes' => $contrat->getLignes(),
                'id' => $personne->getid(),
 //               'cheque' => $contrat->getCheque()
            );
        }
        
        ksort($cPersons, SORT_STRING);
        
        return $this->render('contrat/listbyperson.html.twig', array(
            'titre' => $ctype::title,
            'personnes' => $cPersons
        ));
    }

    /**
     * Render data constructed for listbyproduitAction.
     */
    public function renderListByProduit(array $contrats, AbstractContrat $ctype)
    {
        $countProduits = array();
        $produitById = array();
        foreach ($contrats as $contrat) {
            $produit = $contrat->getProduit();
            $produitId = $produit->getId();
            if (array_key_exists($produitId, $countProduits)) {
                $countProduits[$produitId] += $contrat->getNombre();
            } else {
                $produitById[$produitId] = $produit;
                $countProduits[$produitId] = $contrat->getNombre();
            }
        }
        $contratProduits = array();
        foreach ($produitById as $id => $produit) {
            $contratProduits[$produit->__toString()] = $countProduits[$id];
        }
        ksort($contratProduits, SORT_STRING);
        return $this->render('contrat/listbyproduit.html.twig', array(
            'titre' => $ctype::title,
            'produits' => $contratProduits
        ));
    }

    /**
     * Displays a AbstractContrat entity for showAction
     */
    protected function renderShow(AbstractContrat $contrat)
    {
        $deleteForm = $this->createDeleteForm($contrat);
        $editForm = $this->createEditForm($contrat);
        $newForm = $this->createNewForm($contrat);
        $listForm = $this->createListForm($contrat);
        
        return $this->render('contrat/show.html.twig', array(
            'titre' => $contrat::title,
            'contrat' => $contrat,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
            'new_form' => $newForm->createView(),
            'list_form'=> $listForm->createView(),
            'delete_lignes' => $this->createDeleteLignes($contrat, 'ligne' . $contrat::path),
            'edit_lignes' => $this->createEditLignes($contrat, 'ligne' . $contrat::path)
        ));
    }

    abstract public function newAction(Request $request);

    /**
     * Routine for newAction
     */
    protected function renderNew(AbstractContrat $contrat, $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            foreach ($contrat->getLignes() as $ligne) {
                $ligne->setContrat($contrat);
                $em->persist($ligne);
                $this->addFlash('notice', sprintf('Ligne %d ajoutée', $ligne->getId()));
            }
            $em->flush();
            
            $this->addFlash('notice', sprintf($contrat::title . ' %d ajouté', $contrat->getId()));
            
            return $this->redirectToRoute($contrat::path . '_show', array(
                'id' => $contrat->getId()
            ));
        }
        
        return $this->render('contrat/new.html.twig', array(
            'contrat' => $contrat,
            'titre' => $contrat::title,
            'form' => $form->createView()
        ));
    }
    abstract public function editAction(Request $request, AbstractContrat $contrat);

    protected function renderEdit(AbstractContrat $contrat, $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            $this->addFlash('notice', $contrat::title.' ' . $contrat . ' persisté');
            
            foreach ($contrat->getLignes() as $ligne) {
                $ligne->setContrat($contrat);
                if($ligne->getNombre()>0){
                $em->persist($ligne);
                } else {
                    $em->remove($ligne);
                }
                $this->addFlash('notice', $contrat::title.' ' . $ligne . ' persistée');
            }
            $em->flush();
            
            return $this->redirectToRoute($contrat::path.'_show', array(
                'id' => $contrat->getId()
            ));
        }
        return $this->render('contrat/edit.html.twig', array(
            'titre' => $contrat::title,
            'contrat' => $contrat,
            'edit_form' => $form->createView()
        ));
    }
    public abstract function deleteAction(Request $request, AbstractContrat $contrat);
    
    protected function renderdelete(AbstractContrat $contrat, $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($contrat->getLignes() as $ligne) {
                $em->remove($ligne);
                $this->addFlash('notice', $contrat::title.' ' . $ligne . ' removed');
            }
            $em->remove($contrat);
            $em->flush();
            $this->addFlash('notice', $contrat::title.' ' . $contrat . ' removed');
        }
        
        return $this->redirectToRoute($contrat::path.'_list');
    }
    /**
     * Creates a form to list the contracts.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createListForm(AbstractContrat $contrat)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl($contrat::path . '_list', array(
            'id' => $contrat->getId()
        )))
        ->setMethod('GET')
        ->getForm();
    }
    /**
     * Creates a form to delete a Contrat or HorsContratentity.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm(AbstractContrat $contrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($contrat::path . '_delete', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('DELETE')
            ->getForm();
    }
    /**
     * Creates a form to show a Contrat entity.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createShowForm(AbstractContrat $contrat)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl($contrat::path . '_show', array(
            'id' => $contrat->getId()
        )))
        ->setMethod('GET')
        ->getForm();
    }
    /**
     * Creates a form to edit a Contrat entity.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm(AbstractContrat $contrat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($contrat::path . '_edit', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('GET')
            ->getForm();
    }
    /**
     * Creates a form to add a Contrat entity.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createNewForm(AbstractContrat $contrat)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl($contrat::path . '_new', array(
            'id' => $contrat->getId()
        )))
        ->setMethod('GET')
        ->getForm();
    }
    /**
     * Creates an array of form to delete each line in a Contract.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    protected function createDeleteLignes(AbstractContrat $contrat)
    {
        $deleteforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $deleteforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl($ligne::path . '_delete', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('DELETE')
                ->getForm()
                ->createView();
        }
        return $deleteforms;
    }

    /**
     * Creates an array of form to edit each line in a or HorsContrat Contract.
     *
     * @param AbstractContrat $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    protected function createEditLignes(AbstractContrat $contrat)
    {
        $editforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $editforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl($ligne::path . '_edit', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('GET')
                ->getForm()
                ->createView();
        }
        return $editforms;
    }
}
