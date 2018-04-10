<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 * @ORM\Entity()
 */
class Contrat extends ContratAbstract
{
    /**
     * Add ligne
     *
     * @param LigneContrat $ligne
     *
     * @return Contrat
     */
    public function addLigne(LigneAbstract $ligne)
    {
        if ($ligne->getClass() == "LigneContrat") {
            return parent::addLigneContrat($ligne);
        } else {
            return null;
        }
    }
    
    /**
     * Remove ligne
     *
     * @param LigneContrat $ligne
     *
     * @return LigneContrat
     */
    public function removeLigneContrat(LigneAbstract $ligne)
    {
        if ($ligne->getClass() == "LigneContrat") {
            return parent::removeLigneContrat($ligne);
        } else {
            return null;
        }
    }
}
