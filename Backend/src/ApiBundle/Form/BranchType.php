<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BranchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('company', EntityType::class, ['class' => 'ApiBundle\Entity\Company'])
            ->add('geographical_area', GeographicalAreaType::class,['required' => false])
            ->add('supervisor', EntityType::class, ['class' => 'ApiBundle\Entity\User\Supervisor', 'required' => false])
            ->add('police_phone', TextType::class, ['required' => false])
            ->add('fire_phone', TextType::class, ['required' => false])
            ->add('ambulance_phone', TextType::class, ['required' => false])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Branch',
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
