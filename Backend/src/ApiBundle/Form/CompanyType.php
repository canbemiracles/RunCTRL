<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CompanyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('week_start_on')
            ->add('overtime_hourly_rate')
            ->add('weekend_rate')
            ->add('geographical_area', GeographicalAreaType::class)
            ->add('time_zone', EntityType::class, ['class' => 'ApiBundle\Entity\TimeZone'])
            ->add('currency', EntityType::class, ['class' => 'ApiBundle\Entity\Currency'])
            ->add('date_format', EntityType::class, ['class' => 'ApiBundle\Entity\DateFormat'])
            ->add('time_format', EntityType::class, ['class' => 'ApiBundle\Entity\TimeFormat'])
            ->add('subcategory', EntityType::class, ['class' => 'ApiBundle\Entity\Subcategory'])
            ->add('weekends', CollectionType::class,[
                'entry_type' =>  EntityType::class,
                'allow_add'    => true,
                'by_reference' => false,
                'entry_options' => array(
                    'class' => 'ApiBundle\Entity\ShiftDay'
                ),
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Company',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
