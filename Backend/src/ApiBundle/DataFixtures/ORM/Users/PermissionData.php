<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Employee;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PermissionData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    use FillEntityTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'fields' =>
                    [
                        'permission' => 'branch_index',
                        'description' => 'branch_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_show',
                        'description' => 'branch_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_new',
                        'description' => 'branch_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_edit',
                        'description' => 'branch_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_delete',
                        'description' => 'branch_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_live_data',
                        'description' => 'branch_live_data',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_live_data'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_attach_employee',
                        'description' => 'branch_attach_employee',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_attach_employee'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_index',
                        'description' => 'branch_station_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_station_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_new',
                        'description' => 'branch_station_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_station_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_show',
                        'description' => 'branch_station_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_station_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_edit',
                        'description' => 'branch_station_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_station_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_delete',
                        'description' => 'branch_station_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_branch_station_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_generate_pin',
                        'description' => 'branch_station_generate_pin',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_station_generate_pin'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_qr_code',
                        'description' => 'branch_station_qr_code',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_station_qr_code'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_station_tasks',
                        'description' => 'branch_station_tasks',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_branch_station_tasks'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_index',
                        'description' => 'branch_shift_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_new',
                        'description' => 'branch_shift_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_show',
                        'description' => 'branch_shift_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_edit',
                        'description' => 'branch_shift_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_delete',
                        'description' => 'branch_shift_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_open_employee',
                        'description' => 'branch_shift_open_employee',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_branch_shift_open_employee'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_logout',
                        'description' => 'branch_shift_logout',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_branch_shift_logout'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_accept_open_assignment',
                        'description' => 'branch_shift_accept_open_assignment',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_branch_shift_accept_open_assignment'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_generate_end_of_shift_report',
                        'description' => 'branch_shift_generate_end_of_shift_report',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_branch_shift_generate_end_of_shift_report'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_index',
                        'description' => 'company_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_company_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_new',
                        'description' => 'company_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_company_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_show',
                        'description' => 'company_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_company_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_edit',
                        'description' => 'company_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_company_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_delete',
                        'description' => 'company_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_company_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_live_data',
                        'description' => 'company_live_data',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_company_live_data'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_activate_trial',
                        'description' => 'company_activate_trial',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_company_activate_trial'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'country_index',
                        'description' => 'country_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_country_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'country_new',
                        'description' => 'country_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_country_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'country_show',
                        'description' => 'country_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_country_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'country_edit',
                        'description' => 'country_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_country_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'country_delete',
                        'description' => 'country_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_country_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'currency_index',
                        'description' => 'currency_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_currency_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'currency_new',
                        'description' => 'currency_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_currency_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'currency_show',
                        'description' => 'currency_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_currency_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'currency_edit',
                        'description' => 'currency_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_currency_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'currency_delete',
                        'description' => 'currency_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_currency_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'date_format_index',
                        'description' => 'date_format_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_date_format_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'date_format_new',
                        'description' => 'date_format_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_date_format_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'date_format_show',
                        'description' => 'date_format_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_date_format_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'date_format_edit',
                        'description' => 'date_format_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_date_format_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'date_format_delete',
                        'description' => 'date_format_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_date_format_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'emergency_phone_index',
                        'description' => 'emergency_phone_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_emergency_phone_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'emergency_phone_new',
                        'description' => 'emergency_phone_new',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_emergency_phone_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'emergency_phone_show',
                        'description' => 'emergency_phone_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_emergency_phone_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'emergency_phone_edit',
                        'description' => 'emergency_phone_edit',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_emergency_phone_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'emergency_phone_delete',
                        'description' => 'emergency_phone_delete',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_emergency_phone_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_index',
                        'description' => 'employee_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_employee_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_new',
                        'description' => 'employee_new',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_employee_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_show',
                        'description' => 'employee_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_employee_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_edit',
                        'description' => 'employee_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_employee_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_delete',
                        'description' => 'employee_delete',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_employee_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_avatar',
                        'description' => 'employee_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_employee_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'employee_history',
                        'description' => 'employee_history',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_employee_history'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'geographic_area_index',
                        'description' => 'geographic_area_index',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_geographic_area_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'geographical_area_new',
                        'description' => 'geographical_area_new',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_geographical_area_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'geographical_area_show',
                        'description' => 'geographical_area_show',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_geographical_area_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'geographical_area_edit',
                        'description' => 'geographical_area_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_geographical_area_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'geographical_area_delete',
                        'description' => 'geographical_area_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_geographical_area_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'industry_category_index',
                        'description' => 'industry_category_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_industry_category_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'industry_category_new',
                        'description' => 'industry_category_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_industry_category_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'industry_category_show',
                        'description' => 'industry_category_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_industry_category_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'industry_category_edit',
                        'description' => 'industry_category_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_industry_category_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'industry_category_delete',
                        'description' => 'industry_category_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_industry_category_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'shift_day_index',
                        'description' => 'shift_day_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_shift_day_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'shift_day_new',
                        'description' => 'shift_day_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_shift_day_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'shift_day_show',
                        'description' => 'shift_day_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_shift_day_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'shift_day_edit',
                        'description' => 'shift_day_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_shift_day_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'shift_day_delete',
                        'description' => 'shift_day_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_shift_day_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'subcategory_index',
                        'description' => 'subcategory_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_subcategory_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'subcategory_new',
                        'description' => 'subcategory_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_subcategory_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'subcategory_show',
                        'description' => 'subcategory_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_subcategory_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'subcategory_edit',
                        'description' => 'subcategory_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_subcategory_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'subcategory_delete',
                        'description' => 'subcategory_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_subcategory_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_zone_index',
                        'description' => 'time_zone_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_time_zone_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_zone_new',
                        'description' => 'time_zone_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_zone_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_zone_show',
                        'description' => 'time_zone_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_time_zone_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_zone_edit',
                        'description' => 'time_zone_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_zone_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_zone_delete',
                        'description' => 'time_zone_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_zone_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_index',
                        'description' => 'user_admin_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_new',
                        'description' => 'user_admin_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_show',
                        'description' => 'user_admin_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_edit',
                        'description' => 'user_admin_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_delete',
                        'description' => 'user_admin_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_admin_avatar',
                        'description' => 'user_admin_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_admin_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_index',
                        'description' => 'user_branch_manager_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_new',
                        'description' => 'user_branch_manager_new',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_show',
                        'description' => 'user_branch_manager_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_edit',
                        'description' => 'user_branch_manager_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_manager'),
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_delete',
                        'description' => 'user_branch_manager_delete',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_branch_manager_avatar',
                        'description' => 'user_branch_manager_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_user_branch_manager_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_index',
                        'description' => 'user_comanager_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_user_comanager_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_new',
                        'description' => 'user_comanager_new',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_comanager_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_show',
                        'description' => 'user_comanager_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_user_comanager_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_edit',
                        'description' => 'user_comanager_edit',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_comanager_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_delete',
                        'description' => 'user_comanager_delete',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_comanager_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_comanager_avatar',
                        'description' => 'user_comanager_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_user_comanager_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_device_token',
                        'description' => 'user_device_token',
                        'group' => [
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_user_device_token'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_index',
                        'description' => 'user_office_employee_index',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_user_office_employee_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_new',
                        'description' => 'user_office_employee_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_device'),
                        ],
                    ],
                'reference' => 'permission_user_office_employee_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_show',
                        'description' => 'user_office_employee_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_user_office_employee_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_edit',
                        'description' => 'user_office_employee_edit',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_office_employee_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_delete',
                        'description' => 'user_office_employee_delete',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_office_employee_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_office_employee_avatar',
                        'description' => 'user_office_employee_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_user_office_employee_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_index',
                        'description' => 'user_supervisor_index',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor')
                        ],
                    ],
                'reference' => 'permission_user_supervisor_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_new',
                        'description' => 'user_supervisor_new',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_supervisor_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_show',
                        'description' => 'user_supervisor_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_user_supervisor_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_edit',
                        'description' => 'user_supervisor_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                        ],
                    ],
                'reference' => 'permission_user_supervisor_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_delete',
                        'description' => 'user_supervisor_delete',
                        'group' => [
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_user_supervisor_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'user_supervisor_avatar',
                        'description' => 'user_supervisor_avatar',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_user_supervisor_avatar'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'security_recent_login',
                        'description' => 'security_recent_login',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_security_recent_login'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'origin_role_index',
                        'description' => 'origin_role_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_origin_role_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'origin_role_new',
                        'description' => 'origin_role_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_origin_role_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'get_working_roles',
                        'description' => 'get_working_roles',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_get_working_roles'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'origin_role_show',
                        'description' => 'origin_role_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_origin_role_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'origin_role_edit',
                        'description' => 'origin_role_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_origin_role_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'origin_role_delete',
                        'description' => 'origin_role_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_origin_role_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'temp_role_index',
                        'description' => 'temp_role_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_temp_role_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'temp_role_new',
                        'description' => 'temp_role_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_temp_role_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'temp_role_show',
                        'description' => 'temp_role_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_temp_role_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'temp_role_edit',
                        'description' => 'temp_role_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_temp_role_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'temp_role_delete',
                        'description' => 'temp_role_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_temp_role_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_cashier_report_index',
                        'description' => 'report_cashier_report_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_cashier_report_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_cashier_report_new',
                        'description' => 'report_cashier_report_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_cashier_report_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_cashier_report_show',
                        'description' => 'report_cashier_report_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_cashier_report_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_cashier_report_edit',
                        'description' => 'report_cashier_report_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_report_cashier_report_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_cashier_report_delete',
                        'description' => 'report_cashier_report_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_report_cashier_report_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_commodity_report_index',
                        'description' => 'report_commodity_report_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_commodity_report_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_commodity_report_new',
                        'description' => 'report_commodity_report_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_commodity_report_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_commodity_report_show',
                        'description' => 'report_commodity_report_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_commodity_report_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_commodity_report_edit',
                        'description' => 'report_commodity_report_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_report_commodity_report_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_commodity_report_delete',
                        'description' => 'report_commodity_report_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_report_commodity_report_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_problem_report_index',
                        'description' => 'report_problem_report_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_problem_report_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_problem_report_new',
                        'description' => 'report_problem_report_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_problem_report_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_problem_report_show',
                        'description' => 'report_problem_report_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_report_problem_report_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_problem_report_edit',
                        'description' => 'report_problem_report_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_report_problem_report_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'report_problem_report_delete',
                        'description' => 'report_problem_report_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_report_problem_report_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'end_of_shift_report_index',
                        'description' => 'end_of_shift_report_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_manager'),
                        ],
                    ],
                'reference' => 'permission_end_of_shift_report_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_roles_index',
                        'description' => 'recommendations_roles_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_recommendations_roles_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_roles_new',
                        'description' => 'recommendations_roles_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_roles_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_roles_show',
                        'description' => 'recommendations_roles_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_recommendations_roles_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_roles_edit',
                        'description' => 'recommendations_roles_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_roles_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_roles_delete',
                        'description' => 'recommendations_roles_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_roles_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_stations_index',
                        'description' => 'recommendations_stations_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_recommendations_stations_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_stations_new',
                        'description' => 'recommendations_stations_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_stations_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_stations_show',
                        'description' => 'recommendations_stations_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_recommendations_stations_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_stations_edit',
                        'description' => 'recommendations_stations_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_stations_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'recommendations_stations_delete',
                        'description' => 'recommendations_stations_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_recommendations_stations_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'setting_cloudflare',
                        'description' => 'setting_cloudflare',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_setting_cloudflare'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'card_new',
                        'description' => 'card_new',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_card_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'card_show',
                        'description' => 'card_show',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_card_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'card_edit',
                        'description' => 'card_edit',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_card_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'card_delete',
                        'description' => 'card_delete',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_card_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'history_card_payment_new',
                        'description' => 'history_card_payment_new',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_history_card_payment_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'history_card_payment_show',
                        'description' => 'history_card_payment_show',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_history_card_payment_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'history_card_payment_edit',
                        'description' => 'history_card_payment_edit',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_history_card_payment_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'history_card_payment_delete',
                        'description' => 'history_card_payment_delete',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_history_card_payment_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'payments_payment',
                        'description' => 'payments_payment',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_payments_payment'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'payments_done',
                        'description' => 'payments_done',
                        'group' => [
                            $this->getReference('group_owner'),
                            $this->getReference('group_admin')
                        ],
                    ],
                'reference' => 'permission_payments_done'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_branch_index',
                        'description' => 'assignments_notification_branch_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_branch_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_branch_new',
                        'description' => 'assignments_notification_branch_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_branch_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_branch_show',
                        'description' => 'assignments_notification_branch_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_branch_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_branch_edit',
                        'description' => 'assignments_notification_branch_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_branch_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_branch_delete',
                        'description' => 'assignments_notification_branch_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_branch_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_message_index',
                        'description' => 'assignments_notification_message_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_message_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_message_new',
                        'description' => 'assignments_notification_message_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_message_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_message_show',
                        'description' => 'assignments_notification_message_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_message_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_message_edit',
                        'description' => 'assignments_notification_message_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_message_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_message_delete',
                        'description' => 'assignments_notification_message_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_message_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_role_index',
                        'description' => 'assignments_notification_role_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_role_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_role_new',
                        'description' => 'assignments_notification_role_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_role_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_role_show',
                        'description' => 'assignments_notification_role_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_role_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_role_edit',
                        'description' => 'assignments_notification_role_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_role_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_role_delete',
                        'description' => 'assignments_notification_role_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_role_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_station_index',
                        'description' => 'assignments_notification_station_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_station_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_station_new',
                        'description' => 'assignments_notification_station_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_station_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_station_show',
                        'description' => 'assignments_notification_station_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_station_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_station_edit',
                        'description' => 'assignments_notification_station_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_station_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_notification_station_delete',
                        'description' => 'assignments_notification_station_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_notification_station_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_index',
                        'description' => 'assignments_standard_task_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_new',
                        'description' => 'assignments_standard_task_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_show',
                        'description' => 'assignments_standard_task_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_edit',
                        'description' => 'assignments_standard_task_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_delete',
                        'description' => 'assignments_standard_task_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_working_on_it',
                        'description' => 'assignments_standard_task_working_on_it',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_working_on_it'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_snooze',
                        'description' => 'assignments_standard_task_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_standard_task_answer',
                        'description' => 'assignments_standard_task_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_standard_task_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_index',
                        'description' => 'assignments_checklist_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_new',
                        'description' => 'assignments_checklist_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_show',
                        'description' => 'assignments_checklist_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_edit',
                        'description' => 'assignments_checklist_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_delete',
                        'description' => 'assignments_checklist_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_snooze',
                        'description' => 'assignments_checklist_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_checklist_answer',
                        'description' => 'assignments_checklist_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_checklist_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'notifications_index',
                        'description' => 'notifications_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_notifications_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'notifications_show',
                        'description' => 'notifications_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_notifications_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'notification_edit',
                        'description' => 'notification_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_notification_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_index',
                        'description' => 'assignments_questions_answer_list_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_new',
                        'description' => 'assignments_questions_answer_list_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_show',
                        'description' => 'assignments_questions_answer_list_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_edit',
                        'description' => 'assignments_questions_answer_list_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_delete',
                        'description' => 'assignments_questions_answer_list_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_snooze',
                        'description' => 'assignments_questions_answer_list_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_answer_list_answer',
                        'description' => 'assignments_questions_answer_list_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_answer_list_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_index',
                        'description' => 'assignments_questions_numeric_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_new',
                        'description' => 'assignments_questions_numeric_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_show',
                        'description' => 'assignments_questions_numeric_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_edit',
                        'description' => 'assignments_questions_numeric_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_delete',
                        'description' => 'assignments_questions_numeric_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_snooze',
                        'description' => 'assignments_questions_numeric_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_numeric_answer',
                        'description' => 'assignments_questions_numeric_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_numeric_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_index',
                        'description' => 'assignments_questions_range_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_new',
                        'description' => 'assignments_questions_range_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_show',
                        'description' => 'assignments_questions_range_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_edit',
                        'description' => 'assignments_questions_range_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_delete',
                        'description' => 'assignments_questions_range_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_snooze',
                        'description' => 'assignments_questions_range_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_range_answer',
                        'description' => 'assignments_questions_range_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_range_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_index',
                        'description' => 'assignments_questions_text_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_new',
                        'description' => 'assignments_questions_text_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_show',
                        'description' => 'assignments_questions_text_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_edit',
                        'description' => 'assignments_questions_text_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_delete',
                        'description' => 'assignments_questions_text_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_snooze',
                        'description' => 'assignments_questions_text_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_text_answer',
                        'description' => 'assignments_questions_text_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_text_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_index',
                        'description' => 'assignments_questions_yes_no_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_new',
                        'description' => 'assignments_questions_yes_no_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_show',
                        'description' => 'assignments_questions_yes_no_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_edit',
                        'description' => 'assignments_questions_yes_no_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_delete',
                        'description' => 'assignments_questions_yes_no_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_snooze',
                        'description' => 'assignments_questions_yes_no_snooze',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_snooze'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'assignments_questions_yes_no_answer',
                        'description' => 'assignments_questions_yes_no_answer',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_assignments_questions_yes_no_answer'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_index',
                        'description' => 'custom_notification_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_new',
                        'description' => 'custom_notification_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_show',
                        'description' => 'custom_notification_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_edit',
                        'description' => 'custom_notification_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_delete',
                        'description' => 'custom_notification_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'custom_notification_choose_users',
                        'description' => 'custom_notification_choose_users',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_custom_notification_choose_users'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_get_income',
                        'description' => 'branch_get_income',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                        ],
                    ],
                'reference' => 'permission_branch_get_income'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'station_get_income',
                        'description' => 'station_get_income',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_station_get_income'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'station_assignments_and_notifications',
                        'description' => 'station_assignments_and_notifications',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_station_assignments_and_notifications'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'get_current_shift',
                        'description' => 'get_current_shift',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_get_current_shift'
            ],


            [
                'fields' =>
                    [
                        'permission' => 'get_employees_by_branch',
                        'description' => 'get_employees_by_branch',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_get_employees_by_branch'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_detach_employee',
                        'description' => 'branch_detach_employee',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_branch_detach_employee'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_reports',
                        'description' => 'company_reports',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_company_reports'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'company_archived_reports',
                        'description' => 'company_archived_reports',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_company_archived_reports'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'search_reports',
                        'description' => 'search_reports',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_search_reports'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'search_employees',
                        'description' => 'search_employees',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_search_employees'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'list_report_today',
                        'description' => 'list_report_today',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_list_report_today'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'list_assignments',
                        'description' => 'list_assignments',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_list_assignments'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'utc_index',
                        'description' => 'utc_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_utc_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'utc_new',
                        'description' => 'utc_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_utc_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'utc_show',
                        'description' => 'utc_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_utc_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'utc_edit',
                        'description' => 'utc_edit',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_utc_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'utc_delete',
                        'description' => 'utc_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                        ],
                    ],
                'reference' => 'permission_utc_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_format_index',
                        'description' => 'time_format_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_time_format_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_format_new',
                        'description' => 'time_format_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_format_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_format_show',
                        'description' => 'time_format_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_office_employee')
                        ],
                    ],
                'reference' => 'permission_time_format_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_format_edit',
                        'description' => 'time_format_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_format_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'time_format_delete',
                        'description' => 'time_format_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_time_format_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'family_statuses_index',
                        'description' => 'family_statuses_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_family_statuses_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'alert_notifications_index',
                        'description' => 'alert_notifications_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_alert_notifications_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'alert_notification_show',
                        'description' => 'alert_notification_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_alert_notification_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notifications_index',
                        'description' => 'announcement_notifications_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_announcement_notifications_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notification_new',
                        'description' => 'announcement_notification_new',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_announcement_notification_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notification_show',
                        'description' => 'announcement_notification_show',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager')
                        ],
                    ],
                'reference' => 'permission_announcement_notification_show'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notification_edit',
                        'description' => 'announcement_notification_edit',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_announcement_notification_edit'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notification_delete',
                        'description' => 'announcement_notification_delete',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_announcement_notification_delete'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'announcement_notification_choose_users',
                        'description' => 'announcement_notification_choose_users',
                        'group' => [
                            $this->getReference('group_owner')
                        ],
                    ],
                'reference' => 'permission_announcement_notification_choose_users'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_open_branch_manager',
                        'description' => 'branch_shift_open_branch_manager',
                        'group' => [
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_branch_shift_open_branch_manager'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'branch_shift_logout_branch_manager',
                        'description' => 'branch_shift_logout_branch_manager',
                        'group' => [
                            $this->getReference('group_manager')
                        ],
                    ],
                'reference' => 'permission_branch_shift_logout_branch_manager'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'white_country_index',
                        'description' => 'white_country_index',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_white_country_index'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'white_country_new',
                        'description' => 'white_country_new',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_white_country_new'
            ],

            [
                'fields' =>
                    [
                        'permission' => 'white_country_delete',
                        'description' => 'white_country_delete',
                        'group' => [
                            $this->getReference('group_admin'),
                            $this->getReference('group_owner'),
                            $this->getReference('group_supervisor'),
                            $this->getReference('group_manager'),
                            $this->getReference('group_co_manager'),
                            $this->getReference('group_device')
                        ],
                    ],
                'reference' => 'permission_white_country_delete'
            ],


        ];

        foreach ($data as $itemData) {
            /** @var \ApiBundle\Entity\User\Permission $entity */
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\User\Permission::class);
            $manager->persist($entity);

            foreach($entity->getGroups() as $group)
            {
                /** @var ApiBundle\Entity\User\Group $group */
                $group->addPermission($entity);
                $manager->persist($group);
            }
            if(array_key_exists('reference', $itemData)){
                $this->addReference($itemData['reference'], $entity);
            }
        }
        $manager->flush();

    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 240;
    }

}
