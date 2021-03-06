<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class LigneHorsContratType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('produit', EntityType::class, array(
    					'class' => 'App:Amap\Produit',
    					'placeholder' => 'Choisir un produit',
    					'query_builder' => function (EntityRepository $er) {
    					return $er->createQueryBuilder('prod')
    					->orderBy('prod.nomProduit', 'ASC')
    					->addOrderBy('prod.bio')
    					->addOrderBy('prod.t')
    					->addOrderBy('prod.poid');
    					}
    					), [ 'label' => 'Produit : '])
    					->add('nombre', IntegerType::class, [ 'label' => 'Nombre : '])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Amap\LigneHorsContrat'
        ));
    }
}
