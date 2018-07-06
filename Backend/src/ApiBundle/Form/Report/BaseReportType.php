<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 04.10.2017
 * Time: 14:03
 */

namespace ApiBundle\Form\Report;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseReportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('read', TextType::class, ['empty_data' => 0])
            ->add('branch_shift', EntityType::class, ['class' => 'ApiBundle\Entity\BranchShift'])
            ->add('archive', TextType::class, ['empty_data' => 0]);

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}