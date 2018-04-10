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
use App\Entity\Amap\LigneHorsContrat;

/**
 * Contrat controller.
 */
class LigneHorsContratController extends Controller {

    /**
     * Deletes a LigneHorsContrat entity.
     *
     * @Route("/lignehorscontrat/{id}/delete", name="lignehorscontrat_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteLigneContratAction(Request $request, LigneHorsContrat $ligne) {
        $form = $this->createDeleteForm ( $ligne);
        $form->handleRequest ( $request );
        
        if ($form->isSubmitted () && $form->isValid ()) {
            $em = $this->getDoctrine ()->getManager ();
            $em->remove ( $lignet );
            $em->flush ();
            
            $this->addFlash ( 'notice', 'lignehorscontrat ' . $ligne . ' supprimé' );
        }
        
        return $this->redirectToRoute ( "horscontrat_list" );
    }

}
