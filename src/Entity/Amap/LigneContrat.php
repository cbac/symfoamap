<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="ligne_contrat")
 * @ORM\Entity(repositoryClass="App\Repository\Amap\LigneContratRepository")
 *
 */
class LigneContrat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="lignecontrat")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @var Contrat
     *
     * @ORM\ManyToOne(targetEntity="Contrat", inversedBy="lignes")
     * @ORM\JoinColumn(name="contrat_id", referencedColumnName="id")
     */
    private $contrat;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set produit
     *
     * @param \App\Entity\Amap\Produit $produit
     *
     * @return Contrat
     */
    public function setProduit(\App\Entity\Amap\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \App\Entity\Amap\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set contrat
     *
     * @param \App\Entity\Amap\Contrat $contratId
     *
     * @return LigneContrat
     */
    public function setContrat(\App\Entity\Amap\Contrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return \App\Entity\Amap\Contrat
     */
    public function getContrat()
    {
        return $this->Contrat;
    }
    /**
     * Return LigneContrat as a string
     * @return string
     */
    function __toString(){
    	return $this->Contrat->__toString().' '.$this->produit->__toString().' quantité '. $this->nombre;
    }
}
