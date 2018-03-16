<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="amap_produit")
 * @ORM\Entity(repositoryClass="App\Repository\Amap\ProduitRepository")
 */
class Produit
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
     * @var string
     *
     * @ORM\Column(name="nom_produit", type="string", length=60, unique=true)
     */
    private $nomProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="T", type="integer", nullable=true)
     */
    private $t;

    /**
     * @var bool
     *
     * @ORM\Column(name="bio", type="boolean")
     */
    private $bio;

    /**
     * @var float
     *
     * @ORM\Column(name="poid", type="float")
     */
    private $poid;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;
    /**
     * @var array
     * @ORM\OneToMany(targetEntity="Contrat", mappedBy="produit")
     */
    private $contrats;
    
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
     * Set nomProduit
     *
     * @param string $nomProduit
     *
     * @return Produit
     */
    public function setNomProduit($nomProduit)
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    /**
     * Get nomProduit
     *
     * @return string
     */
    public function getNomProduit()
    {
        return $this->nomProduit;
    }

    /**
     * Set t
     *
     * @param integer $t
     *
     * @return Produit
     */
    public function setT($t)
    {
        $this->t = $t;

        return $this;
    }

    /**
     * Get t
     *
     * @return int
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * Set bio
     *
     * @param boolean $bio
     *
     * @return Produit
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return bool
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set poid
     *
     * @param float $poid
     *
     * @return Produit
     */
    public function setPoid($poid)
    {
        $this->poid = $poid;

        return $this;
    }

    /**
     * Get poid
     *
     * @return float
     */
    public function getPoid()
    {
        return $this->poid;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }
    function __toString(){
    	$T = null;
    	if($this->getT()) { $T = 'T'.$this->getT(); }
    	$bio = ($this->getBio())?'bio':'non bio';
    	return $this->getNomProduit().' '.$T.' '.$bio.' '.$this->getPoid().'kg';
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contrats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contrat
     *
     * @param \App\Entity\Amap\Contrats $contrat
     *
     * @return Produit
     */
    public function addContrat(\App\Entity\Amap\Contrat $contrat)
    {
        $this->contrats[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \App\Entity\Amap\Contrats $contrat
     */
    public function removeContrat(\App\Entity\Amap\Contrat $contrat)
    {
        $this->contrats->removeElement($contrat);
    }

    /**
     * Get contrats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrats()
    {
        return $this->contrats;
    }
}
