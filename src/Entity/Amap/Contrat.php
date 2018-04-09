<?php

namespace App\Entity\Amap;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var personne
     *
     * @ORM\OneToOne(targetEntity="Personne", inversedBy="contrat")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $personne;
    /**
     * Lignes de ce contrat
     *
     * On définit l'association avec un orphanRemoval pour faciliter la suppression des lignes
     *
     * @ORM\OneToMany(targetEntity="LigneContrat",
     * 					mappedBy="contrat", orphanRemoval=true)
     * (Doctrine INVERSE SIDE)
     */
    protected $lignes;
    
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * Set personne
     *
     * @param \App\Entity\Amap\Personne $person
     *
     * @return Contrat
     */
    public function setPersonne(\App\Entity\Amap\Personne $person = null)
    {
        $this->personne = $person;

        return $this;
    }

    /**
     * Get personne
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
    	return $this->personne->__toString().' '.$this->personne->__toString();
    }
    /**
     * Add ligne
     *
     * @param LigneContrat $ligne
     *
     * @return Contrat
    
    public function addLigneContrat(LigneContrat $ligne)
    {
        $ligne->setContrat($this);
        $this->lignes->add($ligne);
        return $this;
    }
    /**
     * Remove ligne
     *
     * @param LigneContrat $ligne
     *
     * @return LigneContrat
     */
    public function removeLigneContrat(LigneContrat $ligne)
    {
        if($this->etapes->contains($ligne))
        {
            $this->etapes->removeElement($ligne);
        }
        return $ligne;
    }
    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }
    /**
     * Set lignes
     *
     * @return Contrat
     */
    public function setLignes(Collection $lignes)
    {
            $this->lignes = $lignes;
        return $this;
    }
}
