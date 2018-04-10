<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;

class HorsContratType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('personne', EntityType::class, array(
    			'class' => 'App:Amap\Personne',
            	'placeholder' => 'Choisir une personne',
    			'query_builder' => function (EntityRepository $er) {
    			return $er->createQueryBuilder('pers')
    			->orderBy('pers.nom', 'ASC')
    			->addOrderBy('pers.prenom');
    			}
    			));
    	$builder->add('lignes', CollectionType::class, array(
    	    'entry_type' => LigneHorsContratType::class,
    	    'entry_options' => array('label' => false),
    	    'allow_add' => true,
    	    'by_reference' => false,
    	    'allow_delete' => true,
    	));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Amap\HorsContrat'
        ));
    }
}
