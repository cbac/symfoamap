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
    	return $this->personne->__toString().' '.$this->produit->__toString().' quantité '. $this->nombre;
    }
    /**
     * Add etape
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
     * Remove etape
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
    public function getLignees()
    {
        return $this->lignes;
    }
}
