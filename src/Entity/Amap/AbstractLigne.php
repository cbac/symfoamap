<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"lignecontrat" = "LigneContrat", "lignehorscontrat" = "LigneHorsContrat"})
 */

abstract class AbstractLigne
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
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer")
     */
    private $nombre;
    
    /**
     * @var AbstractContrat
     *
     * @ORM\ManyToOne(targetEntity="AbstractContrat", inversedBy="lignes")
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
     * Set nombre
     *
     * @param int $nombre
     *
     * @return LigneContrat
     */
    public function setNombre( $nombre)
    {
        $this->nombre = $nombre;
        
        return $this;
    }
    
    /**
     * Get nombre
     *
     * @return int
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    /**
     * Set contrat
     *
     * @param \App\Entity\Amap\AbstractContrat $contrat
     *
     * @return LigneContrat
     */
    public function setContrat(\App\Entity\Amap\AbstractContrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return \App\Entity\Amap\AbstractContrat
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
    	return $this::title.' '. $this->id. ' produit '.$this->produit->__toString().' quantité '. $this->nombre;
    }
}
