<?php

namespace ApiBundle\Form\User;

use ApiBundle\Form\PhoneNumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login')
            ->add('first_name')
            ->add('last_name')
            ->add('phone_number', PhoneNumberType::class,['required' => false])
            ->add('company', EntityType::class, ['class' => 'ApiBundle\Entity\Company']);
    }

    public function getParent()
    {
        return 'ApiBundle\Form\User\BaseFOSUserType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
