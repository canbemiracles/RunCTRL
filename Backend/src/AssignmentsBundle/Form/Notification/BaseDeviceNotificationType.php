<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 03.10.2017
 * Time: 17:01
 */

namespace AssignmentsBundle\Form\Notification;


use AssignmentsBundle\Form\Notification\Repeat\RepeatMonthDayType;
use AssignmentsBundle\Form\Notification\Repeat\RepeatMonthType;
use AssignmentsBundle\Form\Notification\Repeat\RepeatWeekDayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BaseDeviceNotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('view_time')
            ->add('start_time', DateTimeType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss'])
            ->add('end_time', DateTimeType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd HH:mm:ss'])
            ->add('repeat_unit')
            ->add('repeat_subunit')
            ->add('repeat_month_days', CollectionType::class, [
                'entry_type' => RepeatMonthDayType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('repeat_week_days', CollectionType::class, [
                'entry_type' => RepeatWeekDayType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('repeat_months', CollectionType::class, [
                'entry_type' => RepeatMonthType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('repeat_week')
            ->add('enabled', TextType::class, ['empty_data' => 0]);

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}