<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 28.10.2017
 * Time: 15:37
 */

namespace AssignmentsBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class QuestionPossibleAnswerAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('answer', 'text');
        ;
    }


}