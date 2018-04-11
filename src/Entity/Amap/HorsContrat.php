<?php
namespace App\Entity\Amap;
use Doctrine\ORM\Mapping as ORM;
/**
 * HorsContrat
 * @ORM\Entity()
 */
class HorsContrat extends ContratAbstract
{
    public const  path ='horscontrat';
    public const  title = 'Hors Contrat';
    
    /**
     * Add ligne
     *
     * @param LigneContrat $ligne
     *
     * @return Contrat
     */
    public function addLigne(LigneAbstract $ligne)
    {
        if ($ligne->getClass() == "LigneHorsContrat") {
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
        if ($ligne->getClass() == "LigneHorsContrat") {
            return parent::removeLigneContrat($ligne);
        } else {
            return null;
        }
    }
}
