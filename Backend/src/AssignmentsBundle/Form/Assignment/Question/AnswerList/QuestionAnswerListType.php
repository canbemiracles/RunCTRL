<?php

namespace AssignmentsBundle\Form\Assignment\Question\AnswerList;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionAnswerListType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('possible_answers', CollectionType::class, [
            'entry_type' => PossibleAnswerType::class,
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
            'data_class' => 'AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList',
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
