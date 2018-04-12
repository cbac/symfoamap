<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 * @ORM\Entity()
 */
class Contrat extends AbstractContrat
{
    public const path = 'contrat';
    public const title = 'Contrat';
    /**
     * @var float
     *
     * @ORM\Column(name="cheque", type="float", nullable=true)
     */
    private $cheque;
    /**
     * Set cheque
     *
     * @param float $cheque
     *
     * @return Personne
     */
    public function setCheque($cheque)
    {
        $this->cheque = $cheque;
        
        return $this;
    }
    
    /**
     * Get cheque
     *
     * @return float
     */
    public function getCheque()
    {
        return $this->cheque;
    }
    /**
     * Add ligne
     *
     * @param AbstractLigne $ligne
     *
     * @return Contrat
     */
    public function addLigne(AbstractLigne $ligne)
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
     * @param AbstractLigne $ligne
     *
     * @return AbstractLigne
     */
    public function removeLigneContrat(AbstractLigne $ligne)
    {
        if ($ligne->getClass() == "LigneContrat") {
            return parent::removeLigneContrat($ligne);
        } else {
            return null;
        }
    }
}
