<?php

namespace App\Repository\Amap;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAll()
	{
		return $this->findBy(array(), array('nomProduit' => 'ASC','T' =>'ASC', 'poid' => 'ASC'));
	}
	public function findByID()
	{
		return $this->findBy(array(), array('id' => 'ASC'));
	}
}
