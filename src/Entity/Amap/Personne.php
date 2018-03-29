<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Personne
 *
 * @ORM\Table(name="amap_personne", indexes={@ORM\Index(name="personne_indx", columns={"nom", "prenom"})})
 * @ORM\Entity(repositoryClass="App\Repository\Amap\PersonneRepository")
 * @UniqueEntity(
 *     fields={"nom","prenom"},
 *     errorPath="Personne error",
 *     message="Only one occurence of nom prenom is allowed."
 * )
 * 
 */

class Personne
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var float
     *
     * @ORM\Column(name="cheque", type="float", nullable=true)
     */
    private $cheque;

	/**
	 * @var array 
	 * @ORM\OneToMany(targetEntity="Contrat", mappedBy="personne")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Personne
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

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
     * Return personne as a string
     * @return string
     */
    function __toString(){
    	return $this->getNom().' '.$this->getPrenom();
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
     * @param \App\Entity\Amap\Contrat $contrat
     *
     * @return Personne
     */
    public function addContrat(\App\Entity\Amap\Contrat $contrat)
    {
        $this->contrats[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \App\Entity\Amap\Contrat $contrat
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