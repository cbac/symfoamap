<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Amap\AbstractLigne;
use Symfony\Component\Form\Form;

/**
 * LigneContrat controller.
 */
abstract class AbstractLigneContratController extends Controller
{

    abstract public function listAction();

    /**
     * Lists data constructed in listAction
     */
    protected function renderList(array $lignescontrats, AbstractLigne $obj)
    {
        $deleteforms = array();
        $editforms = array();
        foreach ($lignescontrats as $lignecontrat) {
            $deleteforms[] = $this->createDeleteForm($lignecontrat)->createView();
            $editforms[] = $this->createEditForm($lignecontrat)->createView();
        }
        return $this->render('lignecontrat/list.html.twig', array(
            'lignescontrat' => $lignescontrats,
            'titre' => $obj::title,
            'path' => $obj::path,
            'deleteforms' => $deleteforms,
            'editforms' => $editforms
        ));
    }
    abstract public function showAction(Request $request, AbstractLigne $ligne);
    protected function renderShow(AbstractLigne $ligne, string $path, string $titre){
        $deleteForm = $this->createDeleteForm($ligne,$path);
        return $this->render('lignecontrat/show.html.twig', array(
            'ligne' => $ligne,
            'path' => $path,
            'titre' => $titre,
            'delete_form' => $deleteForm->createView()
        ));
    }
    abstract public function newLigneAction(Request $request);

    protected function renderNew(Form $form, AbstractLigne $ligne)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligne);
            $em->flush();
            
            $this->addFlash('notice', sprintf($ligne::path.' %d ajouté', $ligne->getId()));
            
            return $this->redirectToRoute($ligne::path.'_show', array(
                'id' => $ligne->getId()
            ));
        }
        
        return $this->render('lignecontrat/new.html.twig', array(
            'lignecontrat' => $ligne,
            'titre' => $ligne::title,
            'form' => $form->createView()
        ));
    }
    abstract public function editLigneAction(Request $request, AbstractLigne $lignecontrat);

    protected function renderEdit(Form $editform, AbstractLigne $ligne)
    {
        if ($editform->isSubmitted() && $editform->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ligne);
            $em->flush();
            
            return $this->redirectToRoute($ligne::path.'_show', array(
                'id' => $ligne->getId()
            ));
        }
        $deleteform = $this->createDeleteForm($ligne);
        return $this->render('lignecontrat/edit.html.twig', array(
            'titre' => $ligne::title,
            'lignecontrat' => $ligne,
            'edit_form' => $editform->createView(),
            'delete_form' => $deleteform->createView()
        ));
    }

    /**
     * Deletes a LigneContrat entity.
     */
    abstract public function deleteLigneAction(Request $request, AbstractLigne $ligne);
    protected function renderDelete(Form $form, AbstractLigne $ligne)
    {
       if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ligne);
            $em->flush();
            
            $this->addFlash('notice', $ligne::title.' ' . $ligne . ' supprimé');
        }
        
        return $this->redirectToRoute($ligne::path."_list");
    }

    /**
     * Creates a form to delete a LigneContrat entity.
     *
     * @param AbstractLigne $ligne
     *            The LigneContrat entity
     * @param string $path
     *              url path to distinguish contrat and hors contrat
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm(AbstractLigne $ligne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($ligne::path.'_delete', array(
            'id' => $ligne->getId()
        )))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to edit a LigneContrat entity.
     *
     * @param AbstractLigne $ligne
     *            The lignecontrat entity
     *            
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm(AbstractLigne $ligne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($ligne::path.'_edit', array(
            'id' => $ligne->getId()
        )))
            ->setMethod('GET')
            ->getForm();
    }
}
