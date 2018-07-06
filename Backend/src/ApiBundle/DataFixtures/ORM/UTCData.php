<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\TimeZone;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UTCData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                    'value' => 'Etc/GMT+12',
                    'timeZone' => $this->getReference('time_zone_1'),
               ],
               'reference' => 'utc_1'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+11',
                    'timeZone' => $this->getReference('time_zone_2'),
               ],
               'reference' => 'utc_2'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Midway',
                    'timeZone' => $this->getReference('time_zone_2'),
               ],
               'reference' => 'utc_3'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Niue',
                    'timeZone' => $this->getReference('time_zone_2'),
               ],
               'reference' => 'utc_4'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Pago_Pago',
                    'timeZone' => $this->getReference('time_zone_2'),
               ],
               'reference' => 'utc_5'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+10',
                    'timeZone' => $this->getReference('time_zone_3'),
               ],
               'reference' => 'utc_6'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Honolulu',
                    'timeZone' => $this->getReference('time_zone_3'),
               ],
               'reference' => 'utc_7'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Johnston',
                    'timeZone' => $this->getReference('time_zone_3'),
               ],
               'reference' => 'utc_8'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Rarotonga',
                    'timeZone' => $this->getReference('time_zone_3'),
               ],
               'reference' => 'utc_9'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Tahiti',
                    'timeZone' => $this->getReference('time_zone_3'),
               ],
               'reference' => 'utc_10'
            ],
            [
                'fields' => [
                    'value' => 'America/Anchorage',
                    'timeZone' => $this->getReference('time_zone_4'),
               ],
               'reference' => 'utc_11'
            ],
            [
                'fields' => [
                    'value' => 'America/Juneau',
                    'timeZone' => $this->getReference('time_zone_4'),
               ],
               'reference' => 'utc_12'
            ],
            [
                'fields' => [
                    'value' => 'America/Nome',
                    'timeZone' => $this->getReference('time_zone_4'),
               ],
               'reference' => 'utc_13'
            ],
            [
                'fields' => [
                    'value' => 'America/Sitka',
                    'timeZone' => $this->getReference('time_zone_4'),
               ],
               'reference' => 'utc_14'
            ],
            [
                'fields' => [
                    'value' => 'America/Yakutat',
                    'timeZone' => $this->getReference('time_zone_4'),
               ],
               'reference' => 'utc_15'
            ],
            [
                'fields' => [
                    'value' => 'America/Santa_Isabel',
                    'timeZone' => $this->getReference('time_zone_5'),
               ],
               'reference' => 'utc_16'
            ],
            [
                'fields' => [
                    'value' => 'America/Dawson',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_17'
            ],
            [
                'fields' => [
                    'value' => 'America/Los_Angeles',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_18'
            ],
            [
                'fields' => [
                    'value' => 'America/Tijuana',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_19'
            ],
            [
                'fields' => [
                    'value' => 'America/Vancouver',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_20'
            ],
            [
                'fields' => [
                    'value' => 'America/Whitehorse',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_21'
            ],
            [
                'fields' => [
                    'value' => 'PST8PDT',
                    'timeZone' => $this->getReference('time_zone_6'),
               ],
               'reference' => 'utc_22'
            ],
            [
                'fields' => [
                    'value' => 'America/Creston',
                    'timeZone' => $this->getReference('time_zone_7'),
               ],
               'reference' => 'utc_23'
            ],
            [
                'fields' => [
                    'value' => 'America/Dawson_Creek',
                    'timeZone' => $this->getReference('time_zone_7'),
               ],
               'reference' => 'utc_24'
            ],
            [
                'fields' => [
                    'value' => 'America/Hermosillo',
                    'timeZone' => $this->getReference('time_zone_7'),
               ],
               'reference' => 'utc_25'
            ],
            [
                'fields' => [
                    'value' => 'America/Phoenix',
                    'timeZone' => $this->getReference('time_zone_7'),
               ],
               'reference' => 'utc_26'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+7',
                    'timeZone' => $this->getReference('time_zone_7'),
               ],
               'reference' => 'utc_27'
            ],
            [
                'fields' => [
                    'value' => 'America/Chihuahua',
                    'timeZone' => $this->getReference('time_zone_8'),
               ],
               'reference' => 'utc_28'
            ],
            [
                'fields' => [
                    'value' => 'America/Mazatlan',
                    'timeZone' => $this->getReference('time_zone_8'),
               ],
               'reference' => 'utc_29'
            ],
            [
                'fields' => [
                    'value' => 'America/Boise',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_30'
            ],
            [
                'fields' => [
                    'value' => 'America/Cambridge_Bay',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_31'
            ],
            [
                'fields' => [
                    'value' => 'America/Denver',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_32'
            ],
            [
                'fields' => [
                    'value' => 'America/Edmonton',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_33'
            ],
            [
                'fields' => [
                    'value' => 'America/Inuvik',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_34'
            ],
            [
                'fields' => [
                    'value' => 'America/Ojinaga',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_35'
            ],
            [
                'fields' => [
                    'value' => 'America/Yellowknife',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_36'
            ],
            [
                'fields' => [
                    'value' => 'MST7MDT',
                    'timeZone' => $this->getReference('time_zone_9'),
               ],
               'reference' => 'utc_37'
            ],
            [
                'fields' => [
                    'value' => 'America/Belize',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_38'
            ],
            [
                'fields' => [
                    'value' => 'America/Costa_Rica',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_39'
            ],
            [
                'fields' => [
                    'value' => 'America/El_Salvador',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_40'
            ],
            [
                'fields' => [
                    'value' => 'America/Guatemala',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_41'
            ],
            [
                'fields' => [
                    'value' => 'America/Managua',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_42'
            ],
            [
                'fields' => [
                    'value' => 'America/Tegucigalpa',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_43'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+6',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_44'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Galapagos',
                    'timeZone' => $this->getReference('time_zone_10'),
               ],
               'reference' => 'utc_45'
            ],
            [
                'fields' => [
                    'value' => 'America/Chicago',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_46'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Knox',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_47'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Tell_City',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_48'
            ],
            [
                'fields' => [
                    'value' => 'America/Matamoros',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_49'
            ],
            [
                'fields' => [
                    'value' => 'America/Menominee',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_50'
            ],
            [
                'fields' => [
                    'value' => 'America/North_Dakota/Beulah',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_51'
            ],
            [
                'fields' => [
                    'value' => 'America/North_Dakota/Center',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_52'
            ],
            [
                'fields' => [
                    'value' => 'America/North_Dakota/New_Salem',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_53'
            ],
            [
                'fields' => [
                    'value' => 'America/Rainy_River',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_54'
            ],
            [
                'fields' => [
                    'value' => 'America/Rankin_Inlet',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_55'
            ],
            [
                'fields' => [
                    'value' => 'America/Resolute',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_56'
            ],
            [
                'fields' => [
                    'value' => 'America/Winnipeg',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_57'
            ],
            [
                'fields' => [
                    'value' => 'CST6CDT',
                    'timeZone' => $this->getReference('time_zone_11'),
               ],
               'reference' => 'utc_58'
            ],
            [
                'fields' => [
                    'value' => 'America/Bahia_Banderas',
                    'timeZone' => $this->getReference('time_zone_12'),
               ],
               'reference' => 'utc_59'
            ],
            [
                'fields' => [
                    'value' => 'America/Cancun',
                    'timeZone' => $this->getReference('time_zone_12'),
               ],
               'reference' => 'utc_60'
            ],
            [
                'fields' => [
                    'value' => 'America/Merida',
                    'timeZone' => $this->getReference('time_zone_12'),
               ],
               'reference' => 'utc_61'
            ],
            [
                'fields' => [
                    'value' => 'America/Mexico_City',
                    'timeZone' => $this->getReference('time_zone_12'),
               ],
               'reference' => 'utc_62'
            ],
            [
                'fields' => [
                    'value' => 'America/Monterrey',
                    'timeZone' => $this->getReference('time_zone_12'),
               ],
               'reference' => 'utc_63'
            ],
            [
                'fields' => [
                    'value' => 'America/Regina',
                    'timeZone' => $this->getReference('time_zone_13'),
               ],
               'reference' => 'utc_64'
            ],
            [
                'fields' => [
                    'value' => 'America/Swift_Current',
                    'timeZone' => $this->getReference('time_zone_13'),
               ],
               'reference' => 'utc_65'
            ],
            [
                'fields' => [
                    'value' => 'America/Bogota',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_66'
            ],
            [
                'fields' => [
                    'value' => 'America/Cayman',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_67'
            ],
            [
                'fields' => [
                    'value' => 'America/Coral_Harbour',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_68'
            ],
            [
                'fields' => [
                    'value' => 'America/Eirunepe',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_69'
            ],
            [
                'fields' => [
                    'value' => 'America/Guayaquil',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_70'
            ],
            [
                'fields' => [
                    'value' => 'America/Jamaica',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_71'
            ],
            [
                'fields' => [
                    'value' => 'America/Lima',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_72'
            ],
            [
                'fields' => [
                    'value' => 'America/Panama',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_73'
            ],
            [
                'fields' => [
                    'value' => 'America/Rio_Branco',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_74'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+5',
                    'timeZone' => $this->getReference('time_zone_14'),
               ],
               'reference' => 'utc_75'
            ],
            [
                'fields' => [
                    'value' => 'America/Detroit',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_76'
            ],
            [
                'fields' => [
                    'value' => 'America/Havana',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_77'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Petersburg',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_78'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Vincennes',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_79'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Winamac',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_80'
            ],
            [
                'fields' => [
                    'value' => 'America/Iqaluit',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_81'
            ],
            [
                'fields' => [
                    'value' => 'America/Kentucky/Monticello',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_82'
            ],
            [
                'fields' => [
                    'value' => 'America/Louisville',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_83'
            ],
            [
                'fields' => [
                    'value' => 'America/Montreal',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_84'
            ],
            [
                'fields' => [
                    'value' => 'America/Nassau',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_85'
            ],
            [
                'fields' => [
                    'value' => 'America/New_York',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_86'
            ],
            [
                'fields' => [
                    'value' => 'America/Nipigon',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_87'
            ],
            [
                'fields' => [
                    'value' => 'America/Pangnirtung',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_88'
            ],
            [
                'fields' => [
                    'value' => 'America/Port-au-Prince',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_89'
            ],
            [
                'fields' => [
                    'value' => 'America/Thunder_Bay',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_90'
            ],
            [
                'fields' => [
                    'value' => 'America/Toronto',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_91'
            ],
            [
                'fields' => [
                    'value' => 'EST5EDT',
                    'timeZone' => $this->getReference('time_zone_15'),
               ],
               'reference' => 'utc_92'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Marengo',
                    'timeZone' => $this->getReference('time_zone_16'),
               ],
               'reference' => 'utc_93'
            ],
            [
                'fields' => [
                    'value' => 'America/Indiana/Vevay',
                    'timeZone' => $this->getReference('time_zone_16'),
               ],
               'reference' => 'utc_94'
            ],
            [
                'fields' => [
                    'value' => 'America/Indianapolis',
                    'timeZone' => $this->getReference('time_zone_16'),
               ],
               'reference' => 'utc_95'
            ],
            [
                'fields' => [
                    'value' => 'America/Caracas',
                    'timeZone' => $this->getReference('time_zone_17'),
               ],
               'reference' => 'utc_96'
            ],
            [
                'fields' => [
                    'value' => 'America/Asuncion',
                    'timeZone' => $this->getReference('time_zone_18'),
               ],
               'reference' => 'utc_97'
            ],
            [
                'fields' => [
                    'value' => 'America/Glace_Bay',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_98'
            ],
            [
                'fields' => [
                    'value' => 'America/Goose_Bay',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_99'
            ],
            [
                'fields' => [
                    'value' => 'America/Halifax',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_100'
            ],
            [
                'fields' => [
                    'value' => 'America/Moncton',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_101'
            ],
            [
                'fields' => [
                    'value' => 'America/Thule',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_102'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Bermuda',
                    'timeZone' => $this->getReference('time_zone_19'),
               ],
               'reference' => 'utc_103'
            ],
            [
                'fields' => [
                    'value' => 'America/Campo_Grande',
                    'timeZone' => $this->getReference('time_zone_20'),
               ],
               'reference' => 'utc_104'
            ],
            [
                'fields' => [
                    'value' => 'America/Cuiaba',
                    'timeZone' => $this->getReference('time_zone_20'),
               ],
               'reference' => 'utc_105'
            ],
            [
                'fields' => [
                    'value' => 'America/Anguilla',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_106'
            ],
            [
                'fields' => [
                    'value' => 'America/Antigua',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_107'
            ],
            [
                'fields' => [
                    'value' => 'America/Aruba',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_108'
            ],
            [
                'fields' => [
                    'value' => 'America/Barbados',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_109'
            ],
            [
                'fields' => [
                    'value' => 'America/Blanc-Sablon',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_110'
            ],
            [
                'fields' => [
                    'value' => 'America/Boa_Vista',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_111'
            ],
            [
                'fields' => [
                    'value' => 'America/Curacao',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_112'
            ],
            [
                'fields' => [
                    'value' => 'America/Dominica',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_113'
            ],
            [
                'fields' => [
                    'value' => 'America/Grand_Turk',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_114'
            ],
            [
                'fields' => [
                    'value' => 'America/Grenada',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_115'
            ],
            [
                'fields' => [
                    'value' => 'America/Guadeloupe',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_116'
            ],
            [
                'fields' => [
                    'value' => 'America/Guyana',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_117'
            ],
            [
                'fields' => [
                    'value' => 'America/Kralendijk',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_118'
            ],
            [
                'fields' => [
                    'value' => 'America/La_Paz',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_119'
            ],
            [
                'fields' => [
                    'value' => 'America/Lower_Princes',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_120'
            ],
            [
                'fields' => [
                    'value' => 'America/Manaus',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_121'
            ],
            [
                'fields' => [
                    'value' => 'America/Marigot',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_122'
            ],
            [
                'fields' => [
                    'value' => 'America/Martinique',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_123'
            ],
            [
                'fields' => [
                    'value' => 'America/Montserrat',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_124'
            ],
            [
                'fields' => [
                    'value' => 'America/Port_of_Spain',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_125'
            ],
            [
                'fields' => [
                    'value' => 'America/Porto_Velho',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_126'
            ],
            [
                'fields' => [
                    'value' => 'America/Puerto_Rico',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_127'
            ],
            [
                'fields' => [
                    'value' => 'America/Santo_Domingo',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_128'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Barthelemy',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_129'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Kitts',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_130'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Lucia',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_131'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Thomas',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_132'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Vincent',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_133'
            ],
            [
                'fields' => [
                    'value' => 'America/Tortola',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_134'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+4',
                    'timeZone' => $this->getReference('time_zone_21'),
               ],
               'reference' => 'utc_135'
            ],
            [
                'fields' => [
                    'value' => 'America/Santiago',
                    'timeZone' => $this->getReference('time_zone_22'),
               ],
               'reference' => 'utc_136'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Palmer',
                    'timeZone' => $this->getReference('time_zone_22'),
               ],
               'reference' => 'utc_137'
            ],
            [
                'fields' => [
                    'value' => 'America/St_Johns',
                    'timeZone' => $this->getReference('time_zone_23'),
               ],
               'reference' => 'utc_138'
            ],
            [
                'fields' => [
                    'value' => 'America/Sao_Paulo',
                    'timeZone' => $this->getReference('time_zone_24'),
               ],
               'reference' => 'utc_139'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/La_Rioja',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_140'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/Rio_Gallegos',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_141'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/Salta',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_142'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/San_Juan',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_143'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/San_Luis',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_144'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/Tucuman',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_145'
            ],
            [
                'fields' => [
                    'value' => 'America/Argentina/Ushuaia',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_146'
            ],
            [
                'fields' => [
                    'value' => 'America/Buenos_Aires',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_147'
            ],
            [
                'fields' => [
                    'value' => 'America/Catamarca',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_148'
            ],
            [
                'fields' => [
                    'value' => 'America/Cordoba',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_149'
            ],
            [
                'fields' => [
                    'value' => 'America/Jujuy',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_150'
            ],
            [
                'fields' => [
                    'value' => 'America/Mendoza',
                    'timeZone' => $this->getReference('time_zone_25'),
               ],
               'reference' => 'utc_151'
            ],
            [
                'fields' => [
                    'value' => 'America/Araguaina',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_152'
            ],
            [
                'fields' => [
                    'value' => 'America/Belem',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_153'
            ],
            [
                'fields' => [
                    'value' => 'America/Cayenne',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_154'
            ],
            [
                'fields' => [
                    'value' => 'America/Fortaleza',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_155'
            ],
            [
                'fields' => [
                    'value' => 'America/Maceio',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_156'
            ],
            [
                'fields' => [
                    'value' => 'America/Paramaribo',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_157'
            ],
            [
                'fields' => [
                    'value' => 'America/Recife',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_158'
            ],
            [
                'fields' => [
                    'value' => 'America/Santarem',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_159'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Rothera',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_160'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Stanley',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_161'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+3',
                    'timeZone' => $this->getReference('time_zone_26'),
               ],
               'reference' => 'utc_162'
            ],
            [
                'fields' => [
                    'value' => 'America/Godthab',
                    'timeZone' => $this->getReference('time_zone_27'),
               ],
               'reference' => 'utc_163'
            ],
            [
                'fields' => [
                    'value' => 'America/Montevideo',
                    'timeZone' => $this->getReference('time_zone_28'),
               ],
               'reference' => 'utc_164'
            ],
            [
                'fields' => [
                    'value' => 'America/Bahia',
                    'timeZone' => $this->getReference('time_zone_29'),
               ],
               'reference' => 'utc_165'
            ],
            [
                'fields' => [
                    'value' => 'America/Noronha',
                    'timeZone' => $this->getReference('time_zone_30'),
               ],
               'reference' => 'utc_166'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/South_Georgia',
                    'timeZone' => $this->getReference('time_zone_30'),
               ],
               'reference' => 'utc_167'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+2',
                    'timeZone' => $this->getReference('time_zone_30'),
               ],
               'reference' => 'utc_168'
            ],
            [
                'fields' => [
                    'value' => 'America/Scoresbysund',
                    'timeZone' => $this->getReference('time_zone_32'),
               ],
               'reference' => 'utc_169'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Azores',
                    'timeZone' => $this->getReference('time_zone_32'),
               ],
               'reference' => 'utc_170'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Cape_Verde',
                    'timeZone' => $this->getReference('time_zone_33'),
               ],
               'reference' => 'utc_171'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT+1',
                    'timeZone' => $this->getReference('time_zone_33'),
               ],
               'reference' => 'utc_172'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Casablanca',
                    'timeZone' => $this->getReference('time_zone_34'),
               ],
               'reference' => 'utc_173'
            ],
            [
                'fields' => [
                    'value' => 'Africa/El_Aaiun',
                    'timeZone' => $this->getReference('time_zone_34'),
               ],
               'reference' => 'utc_174'
            ],
            [
                'fields' => [
                    'value' => 'America/Danmarkshavn',
                    'timeZone' => $this->getReference('time_zone_35'),
               ],
               'reference' => 'utc_175'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT',
                    'timeZone' => $this->getReference('time_zone_35'),
               ],
               'reference' => 'utc_176'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Canary',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_177'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Faeroe',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_178'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Madeira',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_179'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Dublin',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_180'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Guernsey',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_181'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Isle_of_Man',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_182'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Jersey',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_183'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Lisbon',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_184'
            ],
            [
                'fields' => [
                    'value' => 'Europe/London',
                    'timeZone' => $this->getReference('time_zone_36'),
               ],
               'reference' => 'utc_185'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Abidjan',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_186'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Accra',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_187'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Bamako',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_188'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Banjul',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_189'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Bissau',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_190'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Conakry',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_191'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Dakar',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_192'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Freetown',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_193'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Lome',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_194'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Monrovia',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_195'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Nouakchott',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_196'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Ouagadougou',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_197'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Sao_Tome',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_198'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/Reykjavik',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_199'
            ],
            [
                'fields' => [
                    'value' => 'Atlantic/St_Helena',
                    'timeZone' => $this->getReference('time_zone_37'),
               ],
               'reference' => 'utc_200'
            ],
            [
                'fields' => [
                    'value' => 'Arctic/Longyearbyen',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_201'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Amsterdam',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_202'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Andorra',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_203'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Berlin',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_204'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Busingen',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_205'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Gibraltar',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_206'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Luxembourg',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_207'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Malta',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_208'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Monaco',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_209'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Oslo',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_210'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Rome',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_211'
            ],
            [
                'fields' => [
                    'value' => 'Europe/San_Marino',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_212'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Stockholm',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_213'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Vaduz',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_214'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Vatican',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_215'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Vienna',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_216'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Zurich',
                    'timeZone' => $this->getReference('time_zone_38'),
               ],
               'reference' => 'utc_217'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Belgrade',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_218'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Bratislava',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_219'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Budapest',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_220'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Ljubljana',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_221'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Podgorica',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_222'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Prague',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_223'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Tirane',
                    'timeZone' => $this->getReference('time_zone_39'),
               ],
               'reference' => 'utc_224'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Ceuta',
                    'timeZone' => $this->getReference('time_zone_40'),
               ],
               'reference' => 'utc_225'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Brussels',
                    'timeZone' => $this->getReference('time_zone_40'),
               ],
               'reference' => 'utc_226'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Copenhagen',
                    'timeZone' => $this->getReference('time_zone_40'),
               ],
               'reference' => 'utc_227'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Madrid',
                    'timeZone' => $this->getReference('time_zone_40'),
               ],
               'reference' => 'utc_228'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Paris',
                    'timeZone' => $this->getReference('time_zone_40'),
               ],
               'reference' => 'utc_229'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Sarajevo',
                    'timeZone' => $this->getReference('time_zone_41'),
               ],
               'reference' => 'utc_230'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Skopje',
                    'timeZone' => $this->getReference('time_zone_41'),
               ],
               'reference' => 'utc_231'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Warsaw',
                    'timeZone' => $this->getReference('time_zone_41'),
               ],
               'reference' => 'utc_232'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Zagreb',
                    'timeZone' => $this->getReference('time_zone_41'),
               ],
               'reference' => 'utc_233'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Algiers',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_234'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Bangui',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_235'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Brazzaville',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_236'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Douala',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_237'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Kinshasa',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_238'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Lagos',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_239'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Libreville',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_240'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Luanda',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_241'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Malabo',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_242'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Ndjamena',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_243'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Niamey',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_244'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Porto-Novo',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_245'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Tunis',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_246'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-1',
                    'timeZone' => $this->getReference('time_zone_42'),
               ],
               'reference' => 'utc_247'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Windhoek',
                    'timeZone' => $this->getReference('time_zone_43'),
               ],
               'reference' => 'utc_248'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Nicosia',
                    'timeZone' => $this->getReference('time_zone_44'),
               ],
               'reference' => 'utc_249'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Athens',
                    'timeZone' => $this->getReference('time_zone_44'),
               ],
               'reference' => 'utc_250'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Bucharest',
                    'timeZone' => $this->getReference('time_zone_44'),
               ],
               'reference' => 'utc_251'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Chisinau',
                    'timeZone' => $this->getReference('time_zone_44'),
               ],
               'reference' => 'utc_252'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Beirut',
                    'timeZone' => $this->getReference('time_zone_45'),
               ],
               'reference' => 'utc_253'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Cairo',
                    'timeZone' => $this->getReference('time_zone_46'),
               ],
               'reference' => 'utc_254'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Damascus',
                    'timeZone' => $this->getReference('time_zone_47'),
               ],
               'reference' => 'utc_255'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Nicosia',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_256'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Athens',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_257'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Bucharest',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_258'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Chisinau',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_259'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Helsinki',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_260'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Kiev',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_261'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Mariehamn',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_262'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Nicosia',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_263'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Riga',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_264'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Sofia',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_265'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Tallinn',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_266'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Uzhgorod',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_267'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Vilnius',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_268'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Zaporozhye',
                    'timeZone' => $this->getReference('time_zone_48'),
               ],
               'reference' => 'utc_269'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Blantyre',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_270'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Bujumbura',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_271'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Gaborone',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_272'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Harare',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_273'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Johannesburg',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_274'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Kigali',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_275'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Lubumbashi',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_276'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Lusaka',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_277'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Maputo',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_278'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Maseru',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_279'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Mbabane',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_280'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-2',
                    'timeZone' => $this->getReference('time_zone_49'),
               ],
               'reference' => 'utc_281'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Helsinki',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_282'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Kiev',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_283'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Mariehamn',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_284'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Riga',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_285'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Sofia',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_286'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Tallinn',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_287'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Uzhgorod',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_288'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Vilnius',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_289'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Zaporozhye',
                    'timeZone' => $this->getReference('time_zone_50'),
               ],
               'reference' => 'utc_290'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Istanbul',
                    'timeZone' => $this->getReference('time_zone_51'),
               ],
               'reference' => 'utc_291'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Jerusalem',
                    'timeZone' => $this->getReference('time_zone_52'),
               ],
               'reference' => 'utc_292'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Tripoli',
                    'timeZone' => $this->getReference('time_zone_53'),
               ],
               'reference' => 'utc_293'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Amman',
                    'timeZone' => $this->getReference('time_zone_54'),
               ],
               'reference' => 'utc_294'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Baghdad',
                    'timeZone' => $this->getReference('time_zone_55'),
               ],
               'reference' => 'utc_295'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Kaliningrad',
                    'timeZone' => $this->getReference('time_zone_56'),
               ],
               'reference' => 'utc_296'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Minsk',
                    'timeZone' => $this->getReference('time_zone_56'),
               ],
               'reference' => 'utc_297'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Aden',
                    'timeZone' => $this->getReference('time_zone_57'),
               ],
               'reference' => 'utc_298'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Bahrain',
                    'timeZone' => $this->getReference('time_zone_57'),
               ],
               'reference' => 'utc_299'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kuwait',
                    'timeZone' => $this->getReference('time_zone_57'),
               ],
               'reference' => 'utc_300'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Qatar',
                    'timeZone' => $this->getReference('time_zone_57'),
               ],
               'reference' => 'utc_301'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Riyadh',
                    'timeZone' => $this->getReference('time_zone_57'),
               ],
               'reference' => 'utc_302'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Addis_Ababa',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_303'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Asmera',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_304'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Dar_es_Salaam',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_305'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Djibouti',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_306'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Juba',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_307'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Kampala',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_308'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Khartoum',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_309'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Mogadishu',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_310'
            ],
            [
                'fields' => [
                    'value' => 'Africa/Nairobi',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_311'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Syowa',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_312'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-3',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_313'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Antananarivo',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_314'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Comoro',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_315'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Mayotte',
                    'timeZone' => $this->getReference('time_zone_58'),
               ],
               'reference' => 'utc_316'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Kirov',
                    'timeZone' => $this->getReference('time_zone_59'),
               ],
               'reference' => 'utc_317'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Moscow',
                    'timeZone' => $this->getReference('time_zone_59'),
               ],
               'reference' => 'utc_318'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Simferopol',
                    'timeZone' => $this->getReference('time_zone_59'),
               ],
               'reference' => 'utc_319'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Volgograd',
                    'timeZone' => $this->getReference('time_zone_59'),
               ],
               'reference' => 'utc_320'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Astrakhan',
                    'timeZone' => $this->getReference('time_zone_60'),
               ],
               'reference' => 'utc_321'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Samara',
                    'timeZone' => $this->getReference('time_zone_60'),
               ],
               'reference' => 'utc_322'
            ],
            [
                'fields' => [
                    'value' => 'Europe/Ulyanovsk',
                    'timeZone' => $this->getReference('time_zone_60'),
               ],
               'reference' => 'utc_323'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Tehran',
                    'timeZone' => $this->getReference('time_zone_61'),
               ],
               'reference' => 'utc_324'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Dubai',
                    'timeZone' => $this->getReference('time_zone_62'),
               ],
               'reference' => 'utc_325'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Muscat',
                    'timeZone' => $this->getReference('time_zone_62'),
               ],
               'reference' => 'utc_326'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-4',
                    'timeZone' => $this->getReference('time_zone_62'),
               ],
               'reference' => 'utc_327'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Baku',
                    'timeZone' => $this->getReference('time_zone_63'),
               ],
               'reference' => 'utc_328'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Mahe',
                    'timeZone' => $this->getReference('time_zone_64'),
               ],
               'reference' => 'utc_329'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Mauritius',
                    'timeZone' => $this->getReference('time_zone_64'),
               ],
               'reference' => 'utc_330'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Reunion',
                    'timeZone' => $this->getReference('time_zone_64'),
               ],
               'reference' => 'utc_331'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Tbilisi',
                    'timeZone' => $this->getReference('time_zone_65'),
               ],
               'reference' => 'utc_332'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Yerevan',
                    'timeZone' => $this->getReference('time_zone_66'),
               ],
               'reference' => 'utc_333'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kabul',
                    'timeZone' => $this->getReference('time_zone_67'),
               ],
               'reference' => 'utc_334'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Mawson',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_335'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Aqtau',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_336'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Aqtobe',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_337'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Ashgabat',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_338'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Dushanbe',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_339'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Oral',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_340'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Samarkand',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_341'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Tashkent',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_342'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-5',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_343'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Kerguelen',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_344'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Maldives',
                    'timeZone' => $this->getReference('time_zone_68'),
               ],
               'reference' => 'utc_345'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Karachi',
                    'timeZone' => $this->getReference('time_zone_69'),
               ],
               'reference' => 'utc_346'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kolkata',
                    'timeZone' => $this->getReference('time_zone_70'),
               ],
               'reference' => 'utc_347'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Colombo',
                    'timeZone' => $this->getReference('time_zone_71'),
               ],
               'reference' => 'utc_348'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Katmandu',
                    'timeZone' => $this->getReference('time_zone_72'),
               ],
               'reference' => 'utc_349'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Vostok',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_350'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Almaty',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_351'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Bishkek',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_352'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Qyzylorda',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_353'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Urumqi',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_354'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-6',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_355'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Chagos',
                    'timeZone' => $this->getReference('time_zone_73'),
               ],
               'reference' => 'utc_356'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Dhaka',
                    'timeZone' => $this->getReference('time_zone_74'),
               ],
               'reference' => 'utc_357'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Thimphu',
                    'timeZone' => $this->getReference('time_zone_74'),
               ],
               'reference' => 'utc_358'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Yekaterinburg',
                    'timeZone' => $this->getReference('time_zone_75'),
               ],
               'reference' => 'utc_359'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Rangoon',
                    'timeZone' => $this->getReference('time_zone_76'),
               ],
               'reference' => 'utc_360'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Cocos',
                    'timeZone' => $this->getReference('time_zone_76'),
               ],
               'reference' => 'utc_361'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Davis',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_362'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Bangkok',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_363'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Hovd',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_364'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Jakarta',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_365'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Phnom_Penh',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_366'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Pontianak',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_367'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Saigon',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_368'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Vientiane',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_369'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-7',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_370'
            ],
            [
                'fields' => [
                    'value' => 'Indian/Christmas',
                    'timeZone' => $this->getReference('time_zone_77'),
               ],
               'reference' => 'utc_371'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Novokuznetsk',
                    'timeZone' => $this->getReference('time_zone_78'),
               ],
               'reference' => 'utc_372'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Novosibirsk',
                    'timeZone' => $this->getReference('time_zone_78'),
               ],
               'reference' => 'utc_373'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Omsk',
                    'timeZone' => $this->getReference('time_zone_78'),
               ],
               'reference' => 'utc_374'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Hong_Kong',
                    'timeZone' => $this->getReference('time_zone_79'),
               ],
               'reference' => 'utc_375'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Macau',
                    'timeZone' => $this->getReference('time_zone_79'),
               ],
               'reference' => 'utc_376'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Shanghai',
                    'timeZone' => $this->getReference('time_zone_79'),
               ],
               'reference' => 'utc_377'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Krasnoyarsk',
                    'timeZone' => $this->getReference('time_zone_80'),
               ],
               'reference' => 'utc_378'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Brunei',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_379'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kuala_Lumpur',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_380'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kuching',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_381'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Makassar',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_382'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Manila',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_383'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Singapore',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_384'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-8',
                    'timeZone' => $this->getReference('time_zone_81'),
               ],
               'reference' => 'utc_385'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Casey',
                    'timeZone' => $this->getReference('time_zone_82'),
               ],
               'reference' => 'utc_386'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Perth',
                    'timeZone' => $this->getReference('time_zone_82'),
               ],
               'reference' => 'utc_387'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Taipei',
                    'timeZone' => $this->getReference('time_zone_83'),
               ],
               'reference' => 'utc_388'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Choibalsan',
                    'timeZone' => $this->getReference('time_zone_84'),
               ],
               'reference' => 'utc_389'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Ulaanbaatar',
                    'timeZone' => $this->getReference('time_zone_84'),
               ],
               'reference' => 'utc_390'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Irkutsk',
                    'timeZone' => $this->getReference('time_zone_85'),
               ],
               'reference' => 'utc_391'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Dili',
                    'timeZone' => $this->getReference('time_zone_86'),
               ],
               'reference' => 'utc_392'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Jayapura',
                    'timeZone' => $this->getReference('time_zone_86'),
               ],
               'reference' => 'utc_393'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Tokyo',
                    'timeZone' => $this->getReference('time_zone_86'),
               ],
               'reference' => 'utc_394'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-9',
                    'timeZone' => $this->getReference('time_zone_86'),
               ],
               'reference' => 'utc_395'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Palau',
                    'timeZone' => $this->getReference('time_zone_86'),
               ],
               'reference' => 'utc_396'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Pyongyang',
                    'timeZone' => $this->getReference('time_zone_87'),
               ],
               'reference' => 'utc_397'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Seoul',
                    'timeZone' => $this->getReference('time_zone_87'),
               ],
               'reference' => 'utc_398'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Adelaide',
                    'timeZone' => $this->getReference('time_zone_88'),
               ],
               'reference' => 'utc_399'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Broken_Hill',
                    'timeZone' => $this->getReference('time_zone_88'),
               ],
               'reference' => 'utc_400'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Darwin',
                    'timeZone' => $this->getReference('time_zone_89'),
               ],
               'reference' => 'utc_401'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Brisbane',
                    'timeZone' => $this->getReference('time_zone_90'),
               ],
               'reference' => 'utc_402'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Lindeman',
                    'timeZone' => $this->getReference('time_zone_90'),
               ],
               'reference' => 'utc_403'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Melbourne',
                    'timeZone' => $this->getReference('time_zone_91'),
               ],
               'reference' => 'utc_404'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Sydney',
                    'timeZone' => $this->getReference('time_zone_91'),
               ],
               'reference' => 'utc_405'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/DumontDUrville',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_406'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-10',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_407'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Guam',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_408'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Port_Moresby',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_409'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Saipan',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_410'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Truk',
                    'timeZone' => $this->getReference('time_zone_92'),
               ],
               'reference' => 'utc_411'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Currie',
                    'timeZone' => $this->getReference('time_zone_93'),
               ],
               'reference' => 'utc_412'
            ],
            [
                'fields' => [
                    'value' => 'Australia/Hobart',
                    'timeZone' => $this->getReference('time_zone_93'),
               ],
               'reference' => 'utc_413'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Chita',
                    'timeZone' => $this->getReference('time_zone_94'),
               ],
               'reference' => 'utc_414'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Khandyga',
                    'timeZone' => $this->getReference('time_zone_94'),
               ],
               'reference' => 'utc_415'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Yakutsk',
                    'timeZone' => $this->getReference('time_zone_94'),
               ],
               'reference' => 'utc_416'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/Macquarie',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_417'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-11',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_418'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Efate',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_419'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Guadalcanal',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_420'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Kosrae',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_421'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Noumea',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_422'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Ponape',
                    'timeZone' => $this->getReference('time_zone_95'),
               ],
               'reference' => 'utc_423'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Sakhalin',
                    'timeZone' => $this->getReference('time_zone_96'),
               ],
               'reference' => 'utc_424'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Ust-Nera',
                    'timeZone' => $this->getReference('time_zone_96'),
               ],
               'reference' => 'utc_425'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Vladivostok',
                    'timeZone' => $this->getReference('time_zone_96'),
               ],
               'reference' => 'utc_426'
            ],
            [
                'fields' => [
                    'value' => 'Antarctica/McMurdo',
                    'timeZone' => $this->getReference('time_zone_97'),
               ],
               'reference' => 'utc_427'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Auckland',
                    'timeZone' => $this->getReference('time_zone_97'),
               ],
               'reference' => 'utc_428'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-12',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_429'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Funafuti',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_430'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Kwajalein',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_431'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Majuro',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_432'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Nauru',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_433'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Tarawa',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_434'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Wake',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_435'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Wallis',
                    'timeZone' => $this->getReference('time_zone_98'),
               ],
               'reference' => 'utc_436'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Fiji',
                    'timeZone' => $this->getReference('time_zone_99'),
               ],
               'reference' => 'utc_437'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Anadyr',
                    'timeZone' => $this->getReference('time_zone_100'),
               ],
               'reference' => 'utc_438'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kamchatka',
                    'timeZone' => $this->getReference('time_zone_100'),
               ],
               'reference' => 'utc_439'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Magadan',
                    'timeZone' => $this->getReference('time_zone_100'),
               ],
               'reference' => 'utc_440'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Srednekolymsk',
                    'timeZone' => $this->getReference('time_zone_100'),
               ],
               'reference' => 'utc_441'
            ],
            [
                'fields' => [
                    'value' => 'Asia/Kamchatka',
                    'timeZone' => $this->getReference('time_zone_101'),
               ],
               'reference' => 'utc_442'
            ],
            [
                'fields' => [
                    'value' => 'Etc/GMT-13',
                    'timeZone' => $this->getReference('time_zone_102'),
               ],
               'reference' => 'utc_443'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Enderbury',
                    'timeZone' => $this->getReference('time_zone_102'),
               ],
               'reference' => 'utc_444'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Fakaofo',
                    'timeZone' => $this->getReference('time_zone_102'),
               ],
               'reference' => 'utc_445'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Tongatapu',
                    'timeZone' => $this->getReference('time_zone_102'),
               ],
               'reference' => 'utc_446'
            ],
            [
                'fields' => [
                    'value' => 'Pacific/Apia',
                    'timeZone' => $this->getReference('time_zone_103'),
               ],
               'reference' => 'utc_447'
            ],


        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], \ApiBundle\Entity\UTC::class);
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
        return 250;
    }

}
