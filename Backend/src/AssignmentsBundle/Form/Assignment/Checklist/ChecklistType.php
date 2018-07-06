<?php

namespace AssignmentsBundle\Form\Assignment\Checklist;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChecklistType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tasks', CollectionType::class, [
            'entry_type' => ChecklistTasksType::class,
            'allow_add' => true,
            'by_reference' => false,
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AssignmentsBundle\Entity\Assignment\Checklist\Checklist',
            'csrf_protection' => false
        ));
    }

    public function getParent()
    {
        return 'AssignmentsBundle\Form\Assignment\BaseAssignmentType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
