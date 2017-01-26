<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AddContratType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('personne', EntityType::class, array(
    			'class' => 'AppBundle:Amap\Personne',
            	'placeholder' => 'Choisir une personne',
    			'query_builder' => function (EntityRepository $er) {
    			return $er->createQueryBuilder('pers')
    			->orderBy('pers.nom', 'ASC')
    			->addOrderBy('pers.nom');
    			}
    			))
    			->add('produit', EntityType::class, array(
    					'class' => 'AppBundle:Amap\Produit',
    					'placeholder' => 'Choisir un produit',
    					'query_builder' => function (EntityRepository $er) {
    					return $er->createQueryBuilder('prod')
    					->orderBy('prod.nomProduit', 'ASC')
    					->addOrderBy('prod.bio')
    					->addOrderBy('prod.t')
    					->addOrderBy('prod.poid');
    					}
    					))
            	->add('nombre')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Amap\Contrat'
        ));
    }
}
