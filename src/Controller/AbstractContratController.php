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
use App\Entity\Amap\ContratAbstract;

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
    protected function renderList($contrats, $path)
    {
        $deleteforms = array();
        $editforms = array();
        foreach ($contrats as $contrat) {
            $deleteforms[] = $this->createDeleteForm($contrat, $path)->createView();
            $editforms[] = $this->createEditForm($contrat, $path)->createView();
        }
        return $this->render('contrat/list.html.twig', array(
            'titre' => 'Contrats',
            'path_edit' => $path . '_edit',
            'path_new' => $path . '_new',
            'contrats' => $contrats,
            'deleteforms' => $deleteforms,
            'editforms' => $editforms
        ));
    }

    /**
     * Render data constructed for lisbypersonAction.
     */
    protected function renderListByPerson($contrats, $titre)
    {
        $cPersons = array();
        foreach ($contrats as $contrat) {
            $personne = $contrat->getPersonne();
            $cPersons[$personne->__toString()] = array(
                'lignes' => $contrat->getLignes(),
                'id' => $personne->getid(),
                'cheque' => $personne->getCheque()
            );
        }
        
        ksort($cPersons, SORT_STRING);
        
        return $this->render('contrat/listbyperson.html.twig', array(
            'titre' => $titre,
            'personnes' => $cPersons
        ));
    }

    /**
     * Render data constructed for listbyproduitAction.
     */
    public function renderListByProduit($contrats, $titre)
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
            'titre' => $titre,
            'produits' => $contratProduits
        ));
    }

    /**
     * Displays a ContratAbstract entity for showAction
     */
    protected function renderShow(ContratAbstract $contrat, $path, $titre)
    {
        $deleteForm = $this->createDeleteForm($contrat, $path);
        $editForm = $this->createEditForm($contrat, $path);
        
        return $this->render('contrat/show.html.twig', array(
            'titre' => $titre,
            'contrat' => $contrat,
            'delete_form' => $deleteForm->createView(),
            'delete_lignes' => $this->createDeleteLignes($contrat, 'ligne' . $path),
            'edit_lignes' => $this->createEditLignes($contrat, 'ligne' . $path),
            'edit_form' => $editForm->createView()
        ));
    }

    abstract public function newAction(Request $request);

    /**
     * Routine for newAction
     */
    protected function renderNew(ContratAbstract $contrat, $form, $path, $titre)
    {
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
            
            $this->addFlash('notice', sprintf($titre . ' %d ajouté', $contrat->getId()));
            
            return $this->redirectToRoute($path . '_show', array(
                'id' => $contrat->getId()
            ));
        }
        
        return $this->render('contrat/new.html.twig', array(
            'contrat' => $contrat,
            'titre' => $titre,
            'form' => $form->createView()
        ));
    }
    abstract public function editAction(Request $request, ContratAbstract $contrat);

    protected function renderEdit(ContratAbstract $contrat, $form, $path, $titre)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contrat);
            foreach ($contrat->getLignes() as $ligne) {
                $ligne->setContrat($contrat);
                $this->getDoctrine()
                    ->getManager()
                    ->persist($ligne);
            }
            $em->flush();
            
            return $this->redirectToRoute($path.'_show', array(
                'id' => $contrat->getId()
            ));
        }
        return $this->render('contrat/edit.html.twig', array(
            'titre' => $titre,
            'contrat' => $contrat,
            'edit_form' => $form->createView()
        ));
    }
    public abstract function deleteAction(Request $request, ContratAbstract $contrat);
    
    protected function renderdelete($form, ContratAbstract $contrat, $path, $titre)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contrat);
            $em->flush();
            $this->addFlash('notice', $titre.' ' . $contrat . ' supprimé');
        }
        
        return $this->redirectToRoute($path.'_list');
    }

    /**
     * Creates a form to delete a Contrat or HorsContratentity.
     *
     * @param ContratAbstract $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm(ContratAbstract $contrat, $path)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($path . '_delete', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to edit a Contrat entity.
     *
     * @param ContratAbstract $contrat
     *            The Contrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm(ContratAbstract $contrat, $path)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($path . '_edit', array(
            'id' => $contrat->getId()
        )))
            ->setMethod('GET')
            ->getForm();
    }

    /**
     * Creates an array of form to delete each line in a Contract.
     *
     * @param ContratAbstract $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    protected function createDeleteLignes(ContratAbstract $contrat, $path)
    {
        $deleteforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $deleteforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl($path . '_delete', array(
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
     * @param ContratAbstract $contrat
     *            The Contrat entity
     *            
     * @return array
     */
    protected function createEditLignes(ContratAbstract $contrat, $path)
    {
        $editforms = array();
        foreach ($contrat->getLignes() as $ligne) {
            $editforms[] = $this->createFormBuilder()
                ->setAction($this->generateUrl($path . '_edit', array(
                'id' => $ligne->getId()
            )))
                ->setMethod('GET')
                ->getForm()
                ->createView();
        }
        return $editforms;
    }
}
