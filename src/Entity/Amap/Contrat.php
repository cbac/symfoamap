<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="amap_contrat")
 * @ORM\Entity(repositoryClass="App\Repository\Amap\ContratRepository")
 *
 */
class Contrat
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
     * @var produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="contrats")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @var personne
     *
     * @ORM\ManyToOne(targetEntity="Personne", inversedBy="contrats")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $personne;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer")
     */
    private $nombre;


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
     * Set nombre
     *
     * @param int $nombre
     *
     * @return Contrat
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
     * Get produitId
     *
     * @return \App\Entity\Amap\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set personId
     *
     * @param \App\Entity\Amap\Personne $personId
     *
     * @return Contrat
     */
    public function setPersonne(\App\Entity\Amap\Personne $person = null)
    {
        $this->personne = $person;

        return $this;
    }

    /**
     * Get personId
     *
     * @return \App\Entity\Amap\Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }
    /**
     * Return Contrat as a string
     * @return string
     */
    function __toString(){
    	return $this->personne->__toString().' '.$this->produit->__toString().' quantité '. $this->nombre;
    }
}
