<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 26.10.2017
 * Time: 19:37
 */

namespace AssignmentsBundle\Admin;


use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Role\BranchStationOriginRole;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class QuestionTextAdmin  extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $now = new \DateTime();
        $formMapper->add('title', 'text', ['label' => 'Title for this assignment:'])
            ->add('role', EntityType::class, [
                'class' => BranchStationOriginRole::class,
                'choice_label' => function ($role) {
                    /** @var $role AbstractBranchStationRole*/
                    return 'Branch id: '. $role->getBranchStation()->getBranch()->getId()
                        . ', Station:'. $role->getBranchStation()->getName(). ', Role:' .$role->getRole();
                },
                'label' => 'Branch station role:'
            ])
            ->add('start_time', 'datetime', ['label' => 'Start Time (Current server time: ' . $now->format('Y-m-d H:i:s') . ')'])
            ->add('end_time', 'datetime')
            ->add('repeat_unit', 'choice', array(
                'choices' => array(
                  'One time' => '0',
                  'Daily' => '1',
                  'Weekly' => '2',
                  'Monthly' => '3',
                  'Every year' => '4',
                ),
                'label' => 'Repeat?'
                )
            )
            ->add('snooze_max', 'number', ['label' => 'Maximum snoozes for this task'])
            ->add('view_time', 'number', ['label' => 'View time on table (in seconds)'])
            ->add('priority', 'choice', array(
                'choices' => array(
                    'Notify Manager' => '1',
                    'Notify Supervisor' => '2',
                    'Notify Company Owner' => '3'
                ))
            )
            ->add('importance', 'choice', array(
                'choices' => array(
                    'Low' => '1',
                    'Medium' => '2',
                    'High' => '3'
                ))
            )
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title')
            ->add('start_time')
            ->add('end_time')
            ->add('snooze_max')
            ->add('snooze_count')
            ->add('view_time')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title')
            ->addIdentifier('start_time')
            ->addIdentifier('end_time')
            ->addIdentifier('snooze_max')
            ->addIdentifier('snooze_count')
            ->addIdentifier('view_time')
        ;
    }
}