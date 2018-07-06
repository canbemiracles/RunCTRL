<?php

namespace ApiBundle\Form;

use ApiBundle\Entity\User\BranchManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name')
            ->add('last_name')
            ->add('family_situation')
            ->add('hourly_rate')
            ->add('social_security_number')
            ->add('bonus')
            ->add('branches', CollectionType::class,[
                'entry_type' =>  EntityType::class,
                'allow_add'    => true,
                'by_reference' => false,
                'entry_options' => array(
                    'class' => 'ApiBundle\Entity\Branch'
                ),
            ])
            ->add('roles', CollectionType::class,[
                'entry_type' =>  EntityType::class,
                'allow_add'    => true,
                'by_reference' => false,
                'entry_options' => array(
                    'class' => 'ApiBundle\Entity\Role\AbstractBranchStationRole'
                ),
            ])
            ->add('branch_shifts', CollectionType::class,[
                'entry_type' =>  EntityType::class,
                'allow_add'    => true,
                'by_reference' => false,
                'entry_options' => array(
                    'class' => 'ApiBundle\Entity\BranchShift'
                ),
            ])
            ->add('geographical_area', GeographicalAreaType::class,['required' => false])
            ->add('branch_manager', EntityType::class, ['class' => 'ApiBundle\Entity\User\BranchManager', 'required' => false])
            ->add('phone_number', PhoneNumberType::class,['required' => false]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Employee',
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
