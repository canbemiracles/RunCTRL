<?php

namespace ApiBundle\Form\User\Avatar;

use ApiBundle\Form\File\AvatarFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('avatar', AvatarFileType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'        => 'ApiBundle\Entity\User\Admin',
                'csrf_protection'   => false,
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
