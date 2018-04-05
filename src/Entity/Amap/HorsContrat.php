<?php

namespace App\Entity\Amap;

use Doctrine\ORM\Mapping as ORM;

/**
 * HorsContrat
 *
 * @ORM\Table(name="amap_horscontrat")
 * @ORM\Entity(repositoryClass="App\Repository\Amap\HorsContratRepository")
 *
 */
class HorsContrat
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
     * Set setPersonne
     *
     * @param Personne $person
     *
     * @return HorsContrat
     */
    public function setPersonne(Personne $person = null)
    {
        $this->personne = $person;

        return $this;
    }

    /**
     * Get personnne
     *
     * @return Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }
    
    /**
     * Return HorsContrat as a string
     * @return string
     */
    function __toString(){
    	return $this->personne->__toString().' '.$this->lignes->__toString();
    }
    /**
     * Add ligne
     *
     * @param LigneContrat $ligne
     *
     * @return Contrat
     */
    public function addLigne(LigneContrat $ligne)
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
    public function removeEtape(LigneContrat $ligne)
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
}
