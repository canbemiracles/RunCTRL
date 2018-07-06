<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\TimeZone;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TimeZoneData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                'fields' => [
                    'timeZone' => 'Dateline Standard Time',
                    'offset' => '-12',
                    'isdst' => 0,
                    'text' => '(UTC-12:00) International Date Line West',
                    'abbr' => 'DST',
                ],
                'reference' => 'time_zone_1'
            ],
            [
                'fields' => [
                    'timeZone' => 'UTC-11',
                    'offset' => '-11',
                    'isdst' => 0,
                    'text' => '(UTC-11:00) Coordinated Universal Time-11',
                    'abbr' => 'U',
                ],
                'reference' => 'time_zone_2'
            ],
            [
                'fields' => [
                    'timeZone' => 'Hawaiian Standard Time',
                    'offset' => '-10',
                    'isdst' => 0,
                    'text' => '(UTC-10:00) Hawaii',
                    'abbr' => 'HST',
                ],
                'reference' => 'time_zone_3'
            ],
            [
                'fields' => [
                    'timeZone' => 'Alaskan Standard Time',
                    'offset' => '-8',
                    'isdst' => 1,
                    'text' => '(UTC-09:00) Alaska',
                    'abbr' => 'AKDT',
                ],
                'reference' => 'time_zone_4'
            ],
            [
                'fields' => [
                    'timeZone' => 'Pacific Standard Time (Mexico)',
                    'offset' => '-7',
                    'isdst' => 1,
                    'text' => '(UTC-08:00) Baja California',
                    'abbr' => 'PDT',
                ],
                'reference' => 'time_zone_5'
            ],
            [
                'fields' => [
                    'timeZone' => 'Pacific Standard Time',
                    'offset' => '-7',
                    'isdst' => 1,
                    'text' => '(UTC-08:00) Pacific Time (US & Canada)',
                    'abbr' => 'PDT',
                ],
                'reference' => 'time_zone_6'
            ],
            [
                'fields' => [
                    'timeZone' => 'US Mountain Standard Time',
                    'offset' => '-7',
                    'isdst' => 0,
                    'text' => '(UTC-07:00) Arizona',
                    'abbr' => 'UMST',
                ],
                'reference' => 'time_zone_7'
            ],
            [
                'fields' => [
                    'timeZone' => 'Mountain Standard Time (Mexico)',
                    'offset' => '-6',
                    'isdst' => 1,
                    'text' => '(UTC-07:00) Chihuahua, La Paz, Mazatlan',
                    'abbr' => 'MDT',
                ],
                'reference' => 'time_zone_8'
            ],
            [
                'fields' => [
                    'timeZone' => 'Mountain Standard Time',
                    'offset' => '-6',
                    'isdst' => 1,
                    'text' => '(UTC-07:00) Mountain Time (US & Canada)',
                    'abbr' => 'MDT',
                ],
                'reference' => 'time_zone_9'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central America Standard Time',
                    'offset' => '-6',
                    'isdst' => 0,
                    'text' => '(UTC-06:00) Central America',
                    'abbr' => 'CAST',
                ],
                'reference' => 'time_zone_10'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Standard Time',
                    'offset' => '-5',
                    'isdst' => 1,
                    'text' => '(UTC-06:00) Central Time (US & Canada)',
                    'abbr' => 'CDT',
                ],
                'reference' => 'time_zone_11'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Standard Time (Mexico)',
                    'offset' => '-5',
                    'isdst' => 1,
                    'text' => '(UTC-06:00) Guadalajara, Mexico City, Monterrey',
                    'abbr' => 'CDT',
                ],
                'reference' => 'time_zone_12'
            ],
            [
                'fields' => [
                    'timeZone' => 'Canada Central Standard Time',
                    'offset' => '-6',
                    'isdst' => 0,
                    'text' => '(UTC-06:00) Saskatchewan',
                    'abbr' => 'CCST',
                ],
                'reference' => 'time_zone_13'
            ],
            [
                'fields' => [
                    'timeZone' => 'SA Pacific Standard Time',
                    'offset' => '-5',
                    'isdst' => 0,
                    'text' => '(UTC-05:00) Bogota, Lima, Quito',
                    'abbr' => 'SPST',
                ],
                'reference' => 'time_zone_14'
            ],
            [
                'fields' => [
                    'timeZone' => 'Eastern Standard Time',
                    'offset' => '-4',
                    'isdst' => 1,
                    'text' => '(UTC-05:00) Eastern Time (US & Canada)',
                    'abbr' => 'EDT',
                ],
                'reference' => 'time_zone_15'
            ],
            [
                'fields' => [
                    'timeZone' => 'US Eastern Standard Time',
                    'offset' => '-4',
                    'isdst' => 1,
                    'text' => '(UTC-05:00) Indiana (East)',
                    'abbr' => 'UEDT',
                ],
                'reference' => 'time_zone_16'
            ],
            [
                'fields' => [
                    'timeZone' => 'Venezuela Standard Time',
                    'offset' => '-4.5',
                    'isdst' => 0,
                    'text' => '(UTC-04:30) Caracas',
                    'abbr' => 'VST',
                ],
                'reference' => 'time_zone_17'
            ],
            [
                'fields' => [
                    'timeZone' => 'Paraguay Standard Time',
                    'offset' => '-4',
                    'isdst' => 0,
                    'text' => '(UTC-04:00) Asuncion',
                    'abbr' => 'PYT',
                ],
                'reference' => 'time_zone_18'
            ],
            [
                'fields' => [
                    'timeZone' => 'Atlantic Standard Time',
                    'offset' => '-3',
                    'isdst' => 1,
                    'text' => '(UTC-04:00) Atlantic Time (Canada)',
                    'abbr' => 'ADT',
                ],
                'reference' => 'time_zone_19'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Brazilian Standard Time',
                    'offset' => '-4',
                    'isdst' => 0,
                    'text' => '(UTC-04:00) Cuiaba',
                    'abbr' => 'CBST',
                ],
                'reference' => 'time_zone_20'
            ],
            [
                'fields' => [
                    'timeZone' => 'SA Western Standard Time',
                    'offset' => '-4',
                    'isdst' => 0,
                    'text' => '(UTC-04:00) Georgetown, La Paz, Manaus, San Juan',
                    'abbr' => 'SWST',
                ],
                'reference' => 'time_zone_21'
            ],
            [
                'fields' => [
                    'timeZone' => 'Pacific SA Standard Time',
                    'offset' => '-4',
                    'isdst' => 0,
                    'text' => '(UTC-04:00) Santiago',
                    'abbr' => 'PSST',
                ],
                'reference' => 'time_zone_22'
            ],
            [
                'fields' => [
                    'timeZone' => 'Newfoundland Standard Time',
                    'offset' => '-2.5',
                    'isdst' => 1,
                    'text' => '(UTC-03:30) Newfoundland',
                    'abbr' => 'NDT',
                ],
                'reference' => 'time_zone_23'
            ],
            [
                'fields' => [
                    'timeZone' => 'E. South America Standard Time',
                    'offset' => '-3',
                    'isdst' => 0,
                    'text' => '(UTC-03:00) Brasilia',
                    'abbr' => 'ESAST',
                ],
                'reference' => 'time_zone_24'
            ],
            [
                'fields' => [
                    'timeZone' => 'Argentina Standard Time',
                    'offset' => '-3',
                    'isdst' => 0,
                    'text' => '(UTC-03:00) Buenos Aires',
                    'abbr' => 'AST',
                ],
                'reference' => 'time_zone_25'
            ],
            [
                'fields' => [
                    'timeZone' => 'SA Eastern Standard Time',
                    'offset' => '-3',
                    'isdst' => 0,
                    'text' => '(UTC-03:00) Cayenne, Fortaleza',
                    'abbr' => 'SEST',
                ],
                'reference' => 'time_zone_26'
            ],
            [
                'fields' => [
                    'timeZone' => 'Greenland Standard Time',
                    'offset' => '-3',
                    'isdst' => 1,
                    'text' => '(UTC-03:00) Greenland',
                    'abbr' => 'GDT',
                ],
                'reference' => 'time_zone_27'
            ],
            [
                'fields' => [
                    'timeZone' => 'Montevideo Standard Time',
                    'offset' => '-3',
                    'isdst' => 0,
                    'text' => '(UTC-03:00) Montevideo',
                    'abbr' => 'MST',
                ],
                'reference' => 'time_zone_28'
            ],
            [
                'fields' => [
                    'timeZone' => 'Bahia Standard Time',
                    'offset' => '-3',
                    'isdst' => 0,
                    'text' => '(UTC-03:00) Salvador',
                    'abbr' => 'BST',
                ],
                'reference' => 'time_zone_29'
            ],
            [
                'fields' => [
                    'timeZone' => 'UTC-02',
                    'offset' => '-2',
                    'isdst' => 0,
                    'text' => '(UTC-02:00) Coordinated Universal Time-02',
                    'abbr' => 'U',
                ],
                'reference' => 'time_zone_30'
            ],
            [
                'fields' => [
                    'timeZone' => 'Mid-Atlantic Standard Time',
                    'offset' => '-1',
                    'isdst' => 1,
                    'text' => '(UTC-02:00) Mid-Atlantic - Old',
                    'abbr' => 'MDT',
                ],
                'reference' => 'time_zone_31'
            ],
            [
                'fields' => [
                    'timeZone' => 'Azores Standard Time',
                    'offset' => '0',
                    'isdst' => 1,
                    'text' => '(UTC-01:00) Azores',
                    'abbr' => 'ADT',
                ],
                'reference' => 'time_zone_32'
            ],
            [
                'fields' => [
                    'timeZone' => 'Cape Verde Standard Time',
                    'offset' => '-1',
                    'isdst' => 0,
                    'text' => '(UTC-01:00) Cape Verde Is.',
                    'abbr' => 'CVST',
                ],
                'reference' => 'time_zone_33'
            ],
            [
                'fields' => [
                    'timeZone' => 'Morocco Standard Time',
                    'offset' => '1',
                    'isdst' => 1,
                    'text' => '(UTC) Casablanca',
                    'abbr' => 'MDT',
                ],
                'reference' => 'time_zone_34'
            ],
            [
                'fields' => [
                    'timeZone' => 'UTC',
                    'offset' => '0',
                    'isdst' => 0,
                    'text' => '(UTC) Coordinated Universal Time',
                    'abbr' => 'UTC',
                ],
                'reference' => 'time_zone_35'
            ],
            [
                'fields' => [
                    'timeZone' => 'GMT Standard Time',
                    'offset' => '1',
                    'isdst' => 1,
                    'text' => '(UTC) Dublin, Edinburgh, Lisbon, London',
                    'abbr' => 'GDT',
                ],
                'reference' => 'time_zone_36'
            ],
            [
                'fields' => [
                    'timeZone' => 'Greenwich Standard Time',
                    'offset' => '0',
                    'isdst' => 0,
                    'text' => '(UTC) Monrovia, Reykjavik',
                    'abbr' => 'GST',
                ],
                'reference' => 'time_zone_37'
            ],
            [
                'fields' => [
                    'timeZone' => 'W. Europe Standard Time',
                    'offset' => '2',
                    'isdst' => 1,
                    'text' => '(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
                    'abbr' => 'WEDT',
                ],
                'reference' => 'time_zone_38'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Europe Standard Time',
                    'offset' => '2',
                    'isdst' => 1,
                    'text' => '(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
                    'abbr' => 'CEDT',
                ],
                'reference' => 'time_zone_39'
            ],
            [
                'fields' => [
                    'timeZone' => 'Romance Standard Time',
                    'offset' => '2',
                    'isdst' => 1,
                    'text' => '(UTC+01:00) Brussels, Copenhagen, Madrid, Paris',
                    'abbr' => 'RDT',
                ],
                'reference' => 'time_zone_40'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central European Standard Time',
                    'offset' => '2',
                    'isdst' => 1,
                    'text' => '(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb',
                    'abbr' => 'CEDT',
                ],
                'reference' => 'time_zone_41'
            ],
            [
                'fields' => [
                    'timeZone' => 'W. Central Africa Standard Time',
                    'offset' => '1',
                    'isdst' => 0,
                    'text' => '(UTC+01:00) West Central Africa',
                    'abbr' => 'WCAST',
                ],
                'reference' => 'time_zone_42'
            ],
            [
                'fields' => [
                    'timeZone' => 'Namibia Standard Time',
                    'offset' => '1',
                    'isdst' => 0,
                    'text' => '(UTC+01:00) Windhoek',
                    'abbr' => 'NST',
                ],
                'reference' => 'time_zone_43'
            ],
            [
                'fields' => [
                    'timeZone' => 'GTB Standard Time',
                    'offset' => '3',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) Athens, Bucharest',
                    'abbr' => 'GDT',
                ],
                'reference' => 'time_zone_44'
            ],
            [
                'fields' => [
                    'timeZone' => 'Middle East Standard Time',
                    'offset' => '3',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) Beirut',
                    'abbr' => 'MEDT',
                ],
                'reference' => 'time_zone_45'
            ],
            [
                'fields' => [
                    'timeZone' => 'Egypt Standard Time',
                    'offset' => '2',
                    'isdst' => 0,
                    'text' => '(UTC+02:00) Cairo',
                    'abbr' => 'EST',
                ],
                'reference' => 'time_zone_46'
            ],
            [
                'fields' => [
                    'timeZone' => 'Syria Standard Time',
                    'offset' => '3',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) Damascus',
                    'abbr' => 'SDT',
                ],
                'reference' => 'time_zone_47'
            ],
            [
                'fields' => [
                    'timeZone' => 'E. Europe Standard Time',
                    'offset' => '3',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) E. Europe',
                    'abbr' => 'EEDT',
                ],
                'reference' => 'time_zone_48'
            ],
            [
                'fields' => [
                    'timeZone' => 'South Africa Standard Time',
                    'offset' => '2',
                    'isdst' => 0,
                    'text' => '(UTC+02:00) Harare, Pretoria',
                    'abbr' => 'SAST',
                ],
                'reference' => 'time_zone_49'
            ],
            [
                'fields' => [
                    'timeZone' => 'FLE Standard Time',
                    'offset' => '2',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius',
                    'abbr' => 'FDT',
                ],
                'reference' => 'time_zone_50'
            ],
            [
                'fields' => [
                    'timeZone' => 'Turkey Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Istanbul',
                    'abbr' => 'TDT',
                ],
                'reference' => 'time_zone_51'
            ],
            [
                'fields' => [
                    'timeZone' => 'Israel Standard Time',
                    'offset' => '3',
                    'isdst' => 1,
                    'text' => '(UTC+02:00) Jerusalem',
                    'abbr' => 'JDT',
                ],
                'reference' => 'time_zone_52'
            ],
            [
                'fields' => [
                    'timeZone' => 'Libya Standard Time',
                    'offset' => '2',
                    'isdst' => 0,
                    'text' => '(UTC+02:00) Tripoli',
                    'abbr' => 'LST',
                ],
                'reference' => 'time_zone_53'
            ],
            [
                'fields' => [
                    'timeZone' => 'Jordan Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Amman',
                    'abbr' => 'JST',
                ],
                'reference' => 'time_zone_54'
            ],
            [
                'fields' => [
                    'timeZone' => 'Arabic Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Baghdad',
                    'abbr' => 'AST',
                ],
                'reference' => 'time_zone_55'
            ],
            [
                'fields' => [
                    'timeZone' => 'Kaliningrad Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Kaliningrad, Minsk',
                    'abbr' => 'KST',
                ],
                'reference' => 'time_zone_56'
            ],
            [
                'fields' => [
                    'timeZone' => 'Arab Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Kuwait, Riyadh',
                    'abbr' => 'AST',
                ],
                'reference' => 'time_zone_57'
            ],
            [
                'fields' => [
                    'timeZone' => 'E. Africa Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Nairobi',
                    'abbr' => 'EAST',
                ],
                'reference' => 'time_zone_58'
            ],
            [
                'fields' => [
                    'timeZone' => 'Moscow Standard Time',
                    'offset' => '3',
                    'isdst' => 0,
                    'text' => '(UTC+03:00) Moscow, St. Petersburg, Volgograd',
                    'abbr' => 'MSK',
                ],
                'reference' => 'time_zone_59'
            ],
            [
                'fields' => [
                    'timeZone' => 'Samara Time',
                    'offset' => '4',
                    'isdst' => 0,
                    'text' => '(UTC+04:00) Samara, Ulyanovsk, Saratov',
                    'abbr' => 'SAMT',
                ],
                'reference' => 'time_zone_60'
            ],
            [
                'fields' => [
                    'timeZone' => 'Iran Standard Time',
                    'offset' => '4.5',
                    'isdst' => 1,
                    'text' => '(UTC+03:30) Tehran',
                    'abbr' => 'IDT',
                ],
                'reference' => 'time_zone_61'
            ],
            [
                'fields' => [
                    'timeZone' => 'Arabian Standard Time',
                    'offset' => '4',
                    'isdst' => 0,
                    'text' => '(UTC+04:00) Abu Dhabi, Muscat',
                    'abbr' => 'AST',
                ],
                'reference' => 'time_zone_62'
            ],
            [
                'fields' => [
                    'timeZone' => 'Azerbaijan Standard Time',
                    'offset' => '5',
                    'isdst' => 1,
                    'text' => '(UTC+04:00) Baku',
                    'abbr' => 'ADT',
                ],
                'reference' => 'time_zone_63'
            ],
            [
                'fields' => [
                    'timeZone' => 'Mauritius Standard Time',
                    'offset' => '4',
                    'isdst' => 0,
                    'text' => '(UTC+04:00) Port Louis',
                    'abbr' => 'MST',
                ],
                'reference' => 'time_zone_64'
            ],
            [
                'fields' => [
                    'timeZone' => 'Georgian Standard Time',
                    'offset' => '4',
                    'isdst' => 0,
                    'text' => '(UTC+04:00) Tbilisi',
                    'abbr' => 'GST',
                ],
                'reference' => 'time_zone_65'
            ],
            [
                'fields' => [
                    'timeZone' => 'Caucasus Standard Time',
                    'offset' => '4',
                    'isdst' => 0,
                    'text' => '(UTC+04:00) Yerevan',
                    'abbr' => 'CST',
                ],
                'reference' => 'time_zone_66'
            ],
            [
                'fields' => [
                    'timeZone' => 'Afghanistan Standard Time',
                    'offset' => '4.5',
                    'isdst' => 0,
                    'text' => '(UTC+04:30) Kabul',
                    'abbr' => 'AST',
                ],
                'reference' => 'time_zone_67'
            ],
            [
                'fields' => [
                    'timeZone' => 'West Asia Standard Time',
                    'offset' => '5',
                    'isdst' => 0,
                    'text' => '(UTC+05:00) Ashgabat, Tashkent',
                    'abbr' => 'WAST',
                ],
                'reference' => 'time_zone_68'
            ],
            [
                'fields' => [
                    'timeZone' => 'Pakistan Standard Time',
                    'offset' => '5',
                    'isdst' => 0,
                    'text' => '(UTC+05:00) Islamabad, Karachi',
                    'abbr' => 'PST',
                ],
                'reference' => 'time_zone_69'
            ],
            [
                'fields' => [
                    'timeZone' => 'India Standard Time',
                    'offset' => '5.5',
                    'isdst' => 0,
                    'text' => '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi',
                    'abbr' => 'IST',
                ],
                'reference' => 'time_zone_70'
            ],
            [
                'fields' => [
                    'timeZone' => 'Sri Lanka Standard Time',
                    'offset' => '5.5',
                    'isdst' => 0,
                    'text' => '(UTC+05:30) Sri Jayawardenepura',
                    'abbr' => 'SLST',
                ],
                'reference' => 'time_zone_71'
            ],
            [
                'fields' => [
                    'timeZone' => 'Nepal Standard Time',
                    'offset' => '5.75',
                    'isdst' => 0,
                    'text' => '(UTC+05:45) Kathmandu',
                    'abbr' => 'NST',
                ],
                'reference' => 'time_zone_72'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Asia Standard Time',
                    'offset' => '6',
                    'isdst' => 0,
                    'text' => '(UTC+06:00) Astana',
                    'abbr' => 'CAST',
                ],
                'reference' => 'time_zone_73'
            ],
            [
                'fields' => [
                    'timeZone' => 'Bangladesh Standard Time',
                    'offset' => '6',
                    'isdst' => 0,
                    'text' => '(UTC+06:00) Dhaka',
                    'abbr' => 'BST',
                ],
                'reference' => 'time_zone_74'
            ],
            [
                'fields' => [
                    'timeZone' => 'Ekaterinburg Standard Time',
                    'offset' => '6',
                    'isdst' => 0,
                    'text' => '(UTC+06:00) Ekaterinburg',
                    'abbr' => 'EST',
                ],
                'reference' => 'time_zone_75'
            ],
            [
                'fields' => [
                    'timeZone' => 'Myanmar Standard Time',
                    'offset' => '6.5',
                    'isdst' => 0,
                    'text' => '(UTC+06:30) Yangon (Rangoon)',
                    'abbr' => 'MST',
                ],
                'reference' => 'time_zone_76'
            ],
            [
                'fields' => [
                    'timeZone' => 'SE Asia Standard Time',
                    'offset' => '7',
                    'isdst' => 0,
                    'text' => '(UTC+07:00) Bangkok, Hanoi, Jakarta',
                    'abbr' => 'SAST',
                ],
                'reference' => 'time_zone_77'
            ],
            [
                'fields' => [
                    'timeZone' => 'N. Central Asia Standard Time',
                    'offset' => '7',
                    'isdst' => 0,
                    'text' => '(UTC+07:00) Novosibirsk',
                    'abbr' => 'NCAST',
                ],
                'reference' => 'time_zone_78'
            ],
            [
                'fields' => [
                    'timeZone' => 'China Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
                    'abbr' => 'CST',
                ],
                'reference' => 'time_zone_79'
            ],
            [
                'fields' => [
                    'timeZone' => 'North Asia Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Krasnoyarsk',
                    'abbr' => 'NAST',
                ],
                'reference' => 'time_zone_80'
            ],
            [
                'fields' => [
                    'timeZone' => 'Singapore Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Kuala Lumpur, Singapore',
                    'abbr' => 'MPST',
                ],
                'reference' => 'time_zone_81'
            ],
            [
                'fields' => [
                    'timeZone' => 'W. Australia Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Perth',
                    'abbr' => 'WAST',
                ],
                'reference' => 'time_zone_82'
            ],
            [
                'fields' => [
                    'timeZone' => 'Taipei Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Taipei',
                    'abbr' => 'TST',
                ],
                'reference' => 'time_zone_83'
            ],
            [
                'fields' => [
                    'timeZone' => 'Ulaanbaatar Standard Time',
                    'offset' => '8',
                    'isdst' => 0,
                    'text' => '(UTC+08:00) Ulaanbaatar',
                    'abbr' => 'UST',
                ],
                'reference' => 'time_zone_84'
            ],
            [
                'fields' => [
                    'timeZone' => 'North Asia East Standard Time',
                    'offset' => '9',
                    'isdst' => 0,
                    'text' => '(UTC+09:00) Irkutsk',
                    'abbr' => 'NAEST',
                ],
                'reference' => 'time_zone_85'
            ],
            [
                'fields' => [
                    'timeZone' => 'Tokyo Standard Time',
                    'offset' => '9',
                    'isdst' => 0,
                    'text' => '(UTC+09:00) Osaka, Sapporo, Tokyo',
                    'abbr' => 'TST',
                ],
                'reference' => 'time_zone_86'
            ],
            [
                'fields' => [
                    'timeZone' => 'Korea Standard Time',
                    'offset' => '9',
                    'isdst' => 0,
                    'text' => '(UTC+09:00) Seoul',
                    'abbr' => 'KST',
                ],
                'reference' => 'time_zone_87'
            ],
            [
                'fields' => [
                    'timeZone' => 'Cen. Australia Standard Time',
                    'offset' => '9.5',
                    'isdst' => 0,
                    'text' => '(UTC+09:30) Adelaide',
                    'abbr' => 'CAST',
                ],
                'reference' => 'time_zone_88'
            ],
            [
                'fields' => [
                    'timeZone' => 'AUS Central Standard Time',
                    'offset' => '9.5',
                    'isdst' => 0,
                    'text' => '(UTC+09:30) Darwin',
                    'abbr' => 'ACST',
                ],
                'reference' => 'time_zone_89'
            ],
            [
                'fields' => [
                    'timeZone' => 'E. Australia Standard Time',
                    'offset' => '10',
                    'isdst' => 0,
                    'text' => '(UTC+10:00) Brisbane',
                    'abbr' => 'EAST',
                ],
                'reference' => 'time_zone_90'
            ],
            [
                'fields' => [
                    'timeZone' => 'AUS Eastern Standard Time',
                    'offset' => '10',
                    'isdst' => 0,
                    'text' => '(UTC+10:00) Canberra, Melbourne, Sydney',
                    'abbr' => 'AEST',
                ],
                'reference' => 'time_zone_91'
            ],
            [
                'fields' => [
                    'timeZone' => 'West Pacific Standard Time',
                    'offset' => '10',
                    'isdst' => 0,
                    'text' => '(UTC+10:00) Guam, Port Moresby',
                    'abbr' => 'WPST',
                ],
                'reference' => 'time_zone_92'
            ],
            [
                'fields' => [
                    'timeZone' => 'Tasmania Standard Time',
                    'offset' => '10',
                    'isdst' => 0,
                    'text' => '(UTC+10:00) Hobart',
                    'abbr' => 'TST',
                ],
                'reference' => 'time_zone_93'
            ],
            [
                'fields' => [
                    'timeZone' => 'Yakutsk Standard Time',
                    'offset' => '10',
                    'isdst' => 0,
                    'text' => '(UTC+10:00) Yakutsk',
                    'abbr' => 'YST',
                ],
                'reference' => 'time_zone_94'
            ],
            [
                'fields' => [
                    'timeZone' => 'Central Pacific Standard Time',
                    'offset' => '11',
                    'isdst' => 0,
                    'text' => '(UTC+11:00) Solomon Is., New Caledonia',
                    'abbr' => 'CPST',
                ],
                'reference' => 'time_zone_95'
            ],
            [
                'fields' => [
                    'timeZone' => 'Vladivostok Standard Time',
                    'offset' => '11',
                    'isdst' => 0,
                    'text' => '(UTC+11:00) Vladivostok',
                    'abbr' => 'VST',
                ],
                'reference' => 'time_zone_96'
            ],
            [
                'fields' => [
                    'timeZone' => 'New Zealand Standard Time',
                    'offset' => '12',
                    'isdst' => 0,
                    'text' => '(UTC+12:00) Auckland, Wellington',
                    'abbr' => 'NZST',
                ],
                'reference' => 'time_zone_97'
            ],
            [
                'fields' => [
                    'timeZone' => 'UTC+12',
                    'offset' => '12',
                    'isdst' => 0,
                    'text' => '(UTC+12:00) Coordinated Universal Time+12',
                    'abbr' => 'U',
                ],
                'reference' => 'time_zone_98'
            ],
            [
                'fields' => [
                    'timeZone' => 'Fiji Standard Time',
                    'offset' => '12',
                    'isdst' => 0,
                    'text' => '(UTC+12:00) Fiji',
                    'abbr' => 'FST',
                ],
                'reference' => 'time_zone_99'
            ],
            [
                'fields' => [
                    'timeZone' => 'Magadan Standard Time',
                    'offset' => '12',
                    'isdst' => 0,
                    'text' => '(UTC+12:00) Magadan',
                    'abbr' => 'MST',
                ],
                'reference' => 'time_zone_100'
            ],
            [
                'fields' => [
                    'timeZone' => 'Kamchatka Standard Time',
                    'offset' => '13',
                    'isdst' => 1,
                    'text' => '(UTC+12:00) Petropavlovsk-Kamchatsky - Old',
                    'abbr' => 'KDT',
                ],
                'reference' => 'time_zone_101'
            ],
            [
                'fields' => [
                    'timeZone' => 'Tonga Standard Time',
                    'offset' => '13',
                    'isdst' => 0,
                    'text' => '(UTC+13:00) Nuku\'alofa',
                   'abbr' => 'TST',
               ],
               'reference' => 'time_zone_102'
            ],
            [
               'fields' => [
                   'timeZone' => 'Samoa Standard Time',
                   'offset' => '13',
                   'isdst' => 0,
                   'text' => '(UTC+13:00) Samoa',
                   'abbr' => 'SST',
               ],
               'reference' => 'time_zone_103'
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], TimeZone::class);
            $manager->persist($entity);
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
        return 40;
    }

}
