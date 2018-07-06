<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Country;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                    'name' => 'Abkhazia',
                    'phoneCode' => '+7 840',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GE-AB',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_rub'),
                ],
                'reference' => 'country_abkhazia'
            ],
            [
                'fields' => [
                    'name' => 'Afghanistan',
                    'phoneCode' => '+93',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'AF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_afn'),
                ],
                'reference' => 'country_afghanistan'
            ],
            [
                'fields' => [
                    'name' => 'Albania',
                    'phoneCode' => '+355',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AL',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_all'),
                ],
                'reference' => 'country_albania'
            ],
            [
                'fields' => [
                    'name' => 'Algeria',
                    'phoneCode' => '+213',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'DZ',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_dzd'),
                ],
                'reference' => 'country_algeria'
            ],
            [
                'fields' => [
                    'name' => 'American Samoa',
                    'phoneCode' => '+1 684',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_american_samoa'
            ],
            [
                'fields' => [
                    'name' => 'Andorra',
                    'phoneCode' => '+376',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_andorra'
            ],
            [
                'fields' => [
                    'name' => 'Angola',
                    'phoneCode' => '+244',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aoa'),
                ],
                'reference' => 'country_angola'
            ],
            [
                'fields' => [
                    'name' => 'Anguilla',
                    'phoneCode' => '+1 264',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_anguilla'
            ],
            [
                'fields' => [
                    'name' => 'Antigua and Barbuda',
                    'phoneCode' => '+1 268',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_antigua_and_barbuda'
            ],
            [
                'fields' => [
                    'name' => 'Argentina',
                    'phoneCode' => '+54',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ars'),
                ],
                'reference' => 'country_argentina'
            ],
            [
                'fields' => [
                    'name' => 'Armenia',
                    'phoneCode' => '+374',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AM',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_amd'),
                ],
                'reference' => 'country_armenia'
            ],
            [
                'fields' => [
                    'name' => 'Aruba',
                    'phoneCode' => '+297',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_awg'),
                ],
                'reference' => 'country_aruba'
            ],
            [
                'fields' => [
                    'name' => 'Ascension',
                    'phoneCode' => '+247',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SH-AC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_ascension'
            ],
            [
                'fields' => [
                    'name' => 'Australia',
                    'phoneCode' => '+61',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'AU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_australia'
            ],
            [
                'fields' => [
                    'name' => 'Australian External Territories',
                    'phoneCode' => '+672',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AU-ACT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_australian_external_territories'
            ],
            [
                'fields' => [
                    'name' => 'Austria',
                    'phoneCode' => '+43',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AT',
                    'dateFormat' => $this->getReference('date_format_4'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_austria'
            ],
            [
                'fields' => [
                    'name' => 'Azerbaijan',
                    'phoneCode' => '+994',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AZ',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_azn'),
                ],
                'reference' => 'country_azerbaijan'
            ],
            [
                'fields' => [
                    'name' => 'Bahamas',
                    'phoneCode' => '+1 242',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bsd'),
                ],
                'reference' => 'country_bahamas'
            ],
            [
                'fields' => [
                    'name' => 'Bahrain',
                    'phoneCode' => '+973',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'BH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bhd'),
                ],
                'reference' => 'country_bahrain'
            ],
            [
                'fields' => [
                    'name' => 'Bangladesh',
                    'phoneCode' => '+880',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'BD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bdt'),
                ],
                'reference' => 'country_bangladesh'
            ],
            [
                'fields' => [
                    'name' => 'Barbados',
                    'phoneCode' => '+1 246',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BB',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bbd'),
                ],
                'reference' => 'country_barbados'
            ],
            [
                'fields' => [
                    'name' => 'Barbuda',
                    'phoneCode' => '+1 268',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ATG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bbd'),
                ],
                'reference' => 'country_barbuda'
            ],
            [
                'fields' => [
                    'name' => 'Belarus',
                    'phoneCode' => '+375',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BY',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_byr'),
                ],
                'reference' => 'country_belarus'
            ],
            [
                'fields' => [
                    'name' => 'Belgium',
                    'phoneCode' => '+32',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_belgium'
            ],
            [
                'fields' => [
                    'name' => 'Belize',
                    'phoneCode' => '+501',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BZ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bzd'),
                ],
                'reference' => 'country_belize'
            ],
            [
                'fields' => [
                    'name' => 'Benin',
                    'phoneCode' => '+229',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BJ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_benin'
            ],
            [
                'fields' => [
                    'name' => 'Bermuda',
                    'phoneCode' => '+1 441',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bmd'),
                ],
                'reference' => 'country_bermuda'
            ],
            [
                'fields' => [
                    'name' => 'Bhutan',
                    'phoneCode' => '+975',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_btn'),
                ],
                'reference' => 'country_bhutan'
            ],
            [
                'fields' => [
                    'name' => 'Bolivia',
                    'phoneCode' => '+591',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bob'),
                ],
                'reference' => 'country_bolivia'
            ],
            [
                'fields' => [
                    'name' => 'Bosnia and Herzegovina',
                    'phoneCode' => '+387',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bam'),
                ],
                'reference' => 'country_bosnia_and_herzegovina'
            ],
            [
                'fields' => [
                    'name' => 'Botswana',
                    'phoneCode' => '+267',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bwp'),
                ],
                'reference' => 'country_botswana'
            ],
            [
                'fields' => [
                    'name' => 'Brazil',
                    'phoneCode' => '+55',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'BR',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_brl'),
                ],
                'reference' => 'country_brazil'
            ],
            [
                'fields' => [
                    'name' => 'British Indian Ocean Territory',
                    'phoneCode' => '+246',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'IO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_british_indian_ocean_territory'
            ],
            [
                'fields' => [
                    'name' => 'British Virgin Islands',
                    'phoneCode' => '+1 284',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'VGB',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_british_virgin_islands'
            ],
            [
                'fields' => [
                    'name' => 'Brunei',
                    'phoneCode' => '+673',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bnd'),
                ],
                'reference' => 'country_brunei'
            ],
            [
                'fields' => [
                    'name' => 'Bulgaria',
                    'phoneCode' => '+359',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BG',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_bgn'),
                ],
                'reference' => 'country_bulgaria'
            ],
            [
                'fields' => [
                    'name' => 'Burkina Faso',
                    'phoneCode' => '+226',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_burkina_faso'
            ],
            [
                'fields' => [
                    'name' => 'Burundi',
                    'phoneCode' => '+257',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'BI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_bif'),
                ],
                'reference' => 'country_burundi'
            ],
            [
                'fields' => [
                    'name' => 'Cambodia',
                    'phoneCode' => '+855',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_khr'),
                ],
                'reference' => 'country_cambodia'
            ],
            [
                'fields' => [
                    'name' => 'Cameroon',
                    'phoneCode' => '+237',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_cameroon'
            ],
            [
                'fields' => [
                    'name' => 'Canada',
                    'phoneCode' => '+1',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CA',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_cad'),
                ],
                'reference' => 'country_canada'
            ],
            [
                'fields' => [
                    'name' => 'Cape Verde',
                    'phoneCode' => '+238',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CV',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_cve'),
                ],
                'reference' => 'country_cape_verde'
            ],
            [
                'fields' => [
                    'name' => 'Cayman Islands',
                    'phoneCode' => '+ 345',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_kyd'),
                ],
                'reference' => 'country_cayman_islands'
            ],
            [
                'fields' => [
                    'name' => 'Central African Republic',
                    'phoneCode' => '+236',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_central_african_republic'
            ],
            [
                'fields' => [
                    'name' => 'Chad',
                    'phoneCode' => '+235',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_chad'
            ],
            [
                'fields' => [
                    'name' => 'Chile',
                    'phoneCode' => '+56',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CL',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_clp'),
                ],
                'reference' => 'country_chile'
            ],
            [
                'fields' => [
                    'name' => 'China',
                    'phoneCode' => '+86',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_cny'),
                ],
                'reference' => 'country_china'
            ],
            [
                'fields' => [
                    'name' => 'Christmas Island',
                    'phoneCode' => '+61',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CX',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_christmas_island'
            ],
            [
                'fields' => [
                    'name' => 'Cocos-Keeling Islands',
                    'phoneCode' => '+61',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_cocos-keeling_islands'
            ],
            [
                'fields' => [
                    'name' => 'Colombia',
                    'phoneCode' => '+57',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_cop'),
                ],
                'reference' => 'country_colombia'
            ],
            [
                'fields' => [
                    'name' => 'Comoros',
                    'phoneCode' => '+269',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_kmf'),
                ],
                'reference' => 'country_comoros'
            ],
            [
                'fields' => [
                    'name' => 'Congo',
                    'phoneCode' => '+242',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_congo'
            ],
            [
                'fields' => [
                    'name' => 'Congo, Dem. Rep. of (Zaire)',
                    'phoneCode' => '+243',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_cdf'),
                ],
                'reference' => 'country_congo,_dem._rep._of_(zaire)'
            ],
            [
                'fields' => [
                    'name' => 'Cook Islands',
                    'phoneCode' => '+682',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CK',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nzd'),
                ],
                'reference' => 'country_cook_islands'
            ],
            [
                'fields' => [
                    'name' => 'Costa Rica',
                    'phoneCode' => '+506',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_crc'),
                ],
                'reference' => 'country_costa_rica'
            ],
            [
                'fields' => [
                    'name' => 'Croatia',
                    'phoneCode' => '+385',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'HR',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_hrk'),
                ],
                'reference' => 'country_croatia'
            ],
            [
                'fields' => [
                    'name' => 'Cuba',
                    'phoneCode' => '+53',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_cup'),
                ],
                'reference' => 'country_cuba'
            ],
            [
                'fields' => [
                    'name' => 'Curacao',
                    'phoneCode' => '+599',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CUW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ang'),
                ],
                'reference' => 'country_curacao'
            ],
            [
                'fields' => [
                    'name' => 'Cyprus',
                    'phoneCode' => '+537',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_cyprus'
            ],
            [
                'fields' => [
                    'name' => 'Czech Republic',
                    'phoneCode' => '+420',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CZ',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_czk'),
                ],
                'reference' => 'country_czech_republic'
            ],
            [
                'fields' => [
                    'name' => 'Denmark',
                    'phoneCode' => '+45',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'DK',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_dkk'),
                ],
                'reference' => 'country_denmark'
            ],
            [
                'fields' => [
                    'name' => 'Diego Garcia',
                    'phoneCode' => '+246',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'DG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_diego_garcia'
            ],
            [
                'fields' => [
                    'name' => 'Djibouti',
                    'phoneCode' => '+253',
                    'isState' => 0,
                    'workdayStart' => '6',
                    'countryCode' => 'DJ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_djf'),
                ],
                'reference' => 'country_djibouti'
            ],
            [
                'fields' => [
                    'name' => 'Dominica',
                    'phoneCode' => '+1 767',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'DM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_dominica'
            ],
            [
                'fields' => [
                    'name' => 'Dominican Republic',
                    'phoneCode' => '+1 809',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'DO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_dop'),
                ],
                'reference' => 'country_dominican_republic'
            ],
            [
                'fields' => [
                    'name' => 'East Timor',
                    'phoneCode' => '+670',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TLS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_east_timor'
            ],
            [
                'fields' => [
                    'name' => 'Easter Island',
                    'phoneCode' => '+56',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'EI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_easter_island'
            ],
            [
                'fields' => [
                    'name' => 'Ecuador',
                    'phoneCode' => '+593',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'EC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_ecuador'
            ],
            [
                'fields' => [
                    'name' => 'Egypt',
                    'phoneCode' => '+20',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'EG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_egp'),
                ],
                'reference' => 'country_egypt'
            ],
            [
                'fields' => [
                    'name' => 'El Salvador',
                    'phoneCode' => '+503',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SV',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_el_salvador'
            ],
            [
                'fields' => [
                    'name' => 'Equatorial Guinea',
                    'phoneCode' => '+240',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GQ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_equatorial_guinea'
            ],
            [
                'fields' => [
                    'name' => 'Eritrea',
                    'phoneCode' => '+291',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ER',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ern'),
                ],
                'reference' => 'country_eritrea'
            ],
            [
                'fields' => [
                    'name' => 'Estonia',
                    'phoneCode' => '+372',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'EE',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_estonia'
            ],
            [
                'fields' => [
                    'name' => 'Ethiopia',
                    'phoneCode' => '+251',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ET',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_etb'),
                ],
                'reference' => 'country_ethiopia'
            ],
            [
                'fields' => [
                    'name' => 'Falkland Islands',
                    'phoneCode' => '+500',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FK',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_falkland_islands'
            ],
            [
                'fields' => [
                    'name' => 'Faroe Islands',
                    'phoneCode' => '+298',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_dkk'),
                ],
                'reference' => 'country_faroe_islands'
            ],
            [
                'fields' => [
                    'name' => 'Fiji',
                    'phoneCode' => '+679',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FJ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_fjd'),
                ],
                'reference' => 'country_fiji'
            ],
            [
                'fields' => [
                    'name' => 'Finland',
                    'phoneCode' => '+358',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FI',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_finland'
            ],
            [
                'fields' => [
                    'name' => 'France',
                    'phoneCode' => '+33',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FR',
                    'dateFormat' => $this->getReference('date_format_8'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_france'
            ],
            [
                'fields' => [
                    'name' => 'French Antilles',
                    'phoneCode' => '+596',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'FA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_french_antilles'
            ],
            [
                'fields' => [
                    'name' => 'French Guiana',
                    'phoneCode' => '+594',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_french_guiana'
            ],
            [
                'fields' => [
                    'name' => 'French Polynesia',
                    'phoneCode' => '+689',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_french_polynesia'
            ],
            [
                'fields' => [
                    'name' => 'Gabon',
                    'phoneCode' => '+241',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xaf'),
                ],
                'reference' => 'country_gabon'
            ],
            [
                'fields' => [
                    'name' => 'Gambia',
                    'phoneCode' => '+220',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gmd'),
                ],
                'reference' => 'country_gambia'
            ],
            [
                'fields' => [
                    'name' => 'Georgia',
                    'phoneCode' => '+995',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GE',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_gel'),
                ],
                'reference' => 'country_georgia'
            ],
            [
                'fields' => [
                    'name' => 'Germany',
                    'phoneCode' => '+49',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'DE',
                    'dateFormat' => $this->getReference('date_format_9'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_germany'
            ],
            [
                'fields' => [
                    'name' => 'Ghana',
                    'phoneCode' => '+233',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ghs'),
                ],
                'reference' => 'country_ghana'
            ],
            [
                'fields' => [
                    'name' => 'Gibraltar',
                    'phoneCode' => '+350',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gip'),
                ],
                'reference' => 'country_gibraltar'
            ],
            [
                'fields' => [
                    'name' => 'Greece',
                    'phoneCode' => '+30',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_greece'
            ],
            [
                'fields' => [
                    'name' => 'Greenland',
                    'phoneCode' => '+299',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GL',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_dkk'),
                ],
                'reference' => 'country_greenland'
            ],
            [
                'fields' => [
                    'name' => 'Grenada',
                    'phoneCode' => '+1 473',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_grenada'
            ],
            [
                'fields' => [
                    'name' => 'Guadeloupe',
                    'phoneCode' => '+590',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GP',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_guadeloupe'
            ],
            [
                'fields' => [
                    'name' => 'Guam',
                    'phoneCode' => '+1 671',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_guam'
            ],
            [
                'fields' => [
                    'name' => 'Guatemala',
                    'phoneCode' => '+502',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gtq'),
                ],
                'reference' => 'country_guatemala'
            ],
            [
                'fields' => [
                    'name' => 'Guinea',
                    'phoneCode' => '+224',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gnf'),
                ],
                'reference' => 'country_guinea'
            ],
            [
                'fields' => [
                    'name' => 'Guinea-Bissau',
                    'phoneCode' => '+245',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_guinea-bissau'
            ],
            [
                'fields' => [
                    'name' => 'Guyana',
                    'phoneCode' => '+595',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gyd'),
                ],
                'reference' => 'country_guyana'
            ],
            [
                'fields' => [
                    'name' => 'Haiti',
                    'phoneCode' => '+509',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'HT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_htg'),
                ],
                'reference' => 'country_haiti'
            ],
            [
                'fields' => [
                    'name' => 'Honduras',
                    'phoneCode' => '+504',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'HN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_hnl'),
                ],
                'reference' => 'country_honduras'
            ],
            [
                'fields' => [
                    'name' => 'Hong Kong SAR China',
                    'phoneCode' => '+852',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'HK',
                    'dateFormat' => $this->getReference('date_format_10'),
                    'currency' => $this->getReference('currency_hkd'),
                ],
                'reference' => 'country_hong_kong_sar_china'
            ],
            [
                'fields' => [
                    'name' => 'Hungary',
                    'phoneCode' => '+36',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'HU',
                    'dateFormat' => $this->getReference('date_format_2'),
                    'currency' => $this->getReference('currency_huf'),
                ],
                'reference' => 'country_hungary'
            ],
            [
                'fields' => [
                    'name' => 'Iceland',
                    'phoneCode' => '+354',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'IS',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_isk'),
                ],
                'reference' => 'country_iceland'
            ],
            [
                'fields' => [
                    'name' => 'India',
                    'phoneCode' => '+91',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'IN',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_inr'),
                ],
                'reference' => 'country_india'
            ],
            [
                'fields' => [
                    'name' => 'Indonesia',
                    'phoneCode' => '+62',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ID',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_idr'),
                ],
                'reference' => 'country_indonesia'
            ],
            [
                'fields' => [
                    'name' => 'Iran',
                    'phoneCode' => '+98',
                    'isState' => 0,
                    'workdayStart' => '6',
                    'countryCode' => 'IR',
                    'dateFormat' => $this->getReference('date_format_11'),
                    'currency' => $this->getReference('currency_irr'),
                ],
                'reference' => 'country_iran'
            ],
            [
                'fields' => [
                    'name' => 'Iraq',
                    'phoneCode' => '+964',
                    'isState' => 0,
                    'workdayStart' => '6',
                    'countryCode' => 'IQ',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_iqd'),
                ],
                'reference' => 'country_iraq'
            ],
            [
                'fields' => [
                    'name' => 'Ireland',
                    'phoneCode' => '+353',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'IE',
                    'dateFormat' => $this->getReference('date_format_8'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_ireland'
            ],
            [
                'fields' => [
                    'name' => 'Israel',
                    'phoneCode' => '+972',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'IL',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_ils'),
                ],
                'reference' => 'country_israel'
            ],
            [
                'fields' => [
                    'name' => 'Italy',
                    'phoneCode' => '+39',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'IT',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_italy'
            ],
            [
                'fields' => [
                    'name' => 'Ivory Coast',
                    'phoneCode' => '+225',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CIV',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_ivory_coast'
            ],
            [
                'fields' => [
                    'name' => 'Jamaica',
                    'phoneCode' => '+1 876',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'JM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_jmd'),
                ],
                'reference' => 'country_jamaica'
            ],
            [
                'fields' => [
                    'name' => 'Japan',
                    'phoneCode' => '+81',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'JP',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_jpy'),
                ],
                'reference' => 'country_japan'
            ],
            [
                'fields' => [
                    'name' => 'Jordan',
                    'phoneCode' => '+962',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'JO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_jod'),
                ],
                'reference' => 'country_jordan'
            ],
            [
                'fields' => [
                    'name' => 'Kazakhstan',
                    'phoneCode' => '+7 7',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KZ',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_kzt'),
                ],
                'reference' => 'country_kazakhstan'
            ],
            [
                'fields' => [
                    'name' => 'Kenya',
                    'phoneCode' => '+254',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KE',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_kes'),
                ],
                'reference' => 'country_kenya'
            ],
            [
                'fields' => [
                    'name' => 'Kiribati',
                    'phoneCode' => '+686',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_kiribati'
            ],
            [
                'fields' => [
                    'name' => 'Kuwait',
                    'phoneCode' => '+965',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'KW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_kwd'),
                ],
                'reference' => 'country_kuwait'
            ],
            [
                'fields' => [
                    'name' => 'Kyrgyzstan',
                    'phoneCode' => '+996',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KG',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_kgs'),
                ],
                'reference' => 'country_kyrgyzstan'
            ],
            [
                'fields' => [
                    'name' => 'Laos',
                    'phoneCode' => '+856',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LAO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lak'),
                ],
                'reference' => 'country_laos'
            ],
            [
                'fields' => [
                    'name' => 'Latvia',
                    'phoneCode' => '+371',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LV',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_latvia'
            ],
            [
                'fields' => [
                    'name' => 'Lebanon',
                    'phoneCode' => '+961',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LB',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lbp'),
                ],
                'reference' => 'country_lebanon'
            ],
            [
                'fields' => [
                    'name' => 'Lesotho',
                    'phoneCode' => '+266',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lsl'),
                ],
                'reference' => 'country_lesotho'
            ],
            [
                'fields' => [
                    'name' => 'Liberia',
                    'phoneCode' => '+231',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lrd'),
                ],
                'reference' => 'country_liberia'
            ],
            [
                'fields' => [
                    'name' => 'Libya',
                    'phoneCode' => '+218',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'LY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lyd'),
                ],
                'reference' => 'country_libya'
            ],
            [
                'fields' => [
                    'name' => 'Liechtenstein',
                    'phoneCode' => '+423',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_chf'),
                ],
                'reference' => 'country_liechtenstein'
            ],
            [
                'fields' => [
                    'name' => 'Lithuania',
                    'phoneCode' => '+370',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ltl'),
                ],
                'reference' => 'country_lithuania'
            ],
            [
                'fields' => [
                    'name' => 'Luxembourg',
                    'phoneCode' => '+352',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LT',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_ltl'),
                ],
                'reference' => 'country_luxembourg'
            ],
            [
                'fields' => [
                    'name' => 'Macau SAR China',
                    'phoneCode' => '+853',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MAC',
                    'dateFormat' => $this->getReference('date_format_10'),
                    'currency' => $this->getReference('currency_mop'),
                ],
                'reference' => 'country_macau_sar_china'
            ],
            [
                'fields' => [
                    'name' => 'Macedonia',
                    'phoneCode' => '+389',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MK',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_mkd'),
                ],
                'reference' => 'country_macedonia'
            ],
            [
                'fields' => [
                    'name' => 'Madagascar',
                    'phoneCode' => '+261',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mga'),
                ],
                'reference' => 'country_madagascar'
            ],
            [
                'fields' => [
                    'name' => 'Malawi',
                    'phoneCode' => '+265',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mwk'),
                ],
                'reference' => 'country_malawi'
            ],
            [
                'fields' => [
                    'name' => 'Malaysia',
                    'phoneCode' => '+60',
                    'isState' => 1,
                    'workdayStart' => '7',
                    'countryCode' => 'MY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_myr'),
                ],
                'reference' => 'country_malaysia'
            ],
            [
                'fields' => [
                    'name' => 'Maldives',
                    'phoneCode' => '+960',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'MV',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mvr'),
                ],
                'reference' => 'country_maldives'
            ],
            [
                'fields' => [
                    'name' => 'Mali',
                    'phoneCode' => '+223',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ML',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_mali'
            ],
            [
                'fields' => [
                    'name' => 'Malta',
                    'phoneCode' => '+356',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_malta'
            ],
            [
                'fields' => [
                    'name' => 'Marshall Islands',
                    'phoneCode' => '+692',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_marshall_islands'
            ],
            [
                'fields' => [
                    'name' => 'Martinique',
                    'phoneCode' => '+596',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MQ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_martinique'
            ],
            [
                'fields' => [
                    'name' => 'Mauritania',
                    'phoneCode' => '+222',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mru'),
                ],
                'reference' => 'country_mauritania'
            ],
            [
                'fields' => [
                    'name' => 'Mauritius',
                    'phoneCode' => '+230',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mur'),
                ],
                'reference' => 'country_mauritius'
            ],
            [
                'fields' => [
                    'name' => 'Mayotte',
                    'phoneCode' => '+262',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'YT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_mayotte'
            ],
            [
                'fields' => [
                    'name' => 'Mexico',
                    'phoneCode' => '+52',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'MX',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mxn'),
                ],
                'reference' => 'country_mexico'
            ],
            [
                'fields' => [
                    'name' => 'Micronesia',
                    'phoneCode' => '+691',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'FSM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_micronesia'
            ],
            [
                'fields' => [
                    'name' => 'Midway Island',
                    'phoneCode' => '+1 808',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'UM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_midway_island'
            ],
            [
                'fields' => [
                    'name' => 'Moldova',
                    'phoneCode' => '+373',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mdl'),
                ],
                'reference' => 'country_moldova'
            ],
            [
                'fields' => [
                    'name' => 'Monaco',
                    'phoneCode' => '+377',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_monaco'
            ],
            [
                'fields' => [
                    'name' => 'Mongolia',
                    'phoneCode' => '+976',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MN',
                    'dateFormat' => $this->getReference('date_format_2'),
                    'currency' => $this->getReference('currency_mnt'),
                ],
                'reference' => 'country_mongolia'
            ],
            [
                'fields' => [
                    'name' => 'Montenegro',
                    'phoneCode' => '+382',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ME',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_montenegro'
            ],
            [
                'fields' => [
                    'name' => 'Montserrat',
                    'phoneCode' => '+1664',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_montserrat'
            ],
            [
                'fields' => [
                    'name' => 'Morocco',
                    'phoneCode' => '+212',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mad'),
                ],
                'reference' => 'country_morocco'
            ],
            [
                'fields' => [
                    'name' => 'Myanmar',
                    'phoneCode' => '+95',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_mmk'),
                ],
                'reference' => 'country_myanmar'
            ],
            [
                'fields' => [
                    'name' => 'Namibia',
                    'phoneCode' => '+264',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nad'),
                ],
                'reference' => 'country_namibia'
            ],
            [
                'fields' => [
                    'name' => 'Nauru',
                    'phoneCode' => '+674',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_nauru'
            ],
            [
                'fields' => [
                    'name' => 'Nepal',
                    'phoneCode' => '+977',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'NP',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_npr'),
                ],
                'reference' => 'country_nepal'
            ],
            [
                'fields' => [
                    'name' => 'Netherlands',
                    'phoneCode' => '+31',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NL',
                    'dateFormat' => $this->getReference('date_format_12'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_netherlands'
            ],
            [
                'fields' => [
                    'name' => 'Netherlands Antilles',
                    'phoneCode' => '+599',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'AN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ang'),
                ],
                'reference' => 'country_netherlands_antilles'
            ],
            [
                'fields' => [
                    'name' => 'Nevis',
                    'phoneCode' => '+1 869',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xcd'),
                ],
                'reference' => 'country_nevis'
            ],
            [
                'fields' => [
                    'name' => 'New Caledonia',
                    'phoneCode' => '+687',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xpf'),
                ],
                'reference' => 'country_new_caledonia'
            ],
            [
                'fields' => [
                    'name' => 'New Zealand',
                    'phoneCode' => '+64',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NZ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nzd'),
                ],
                'reference' => 'country_new_zealand'
            ],
            [
                'fields' => [
                    'name' => 'Nicaragua',
                    'phoneCode' => '+505',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nio'),
                ],
                'reference' => 'country_nicaragua'
            ],
            [
                'fields' => [
                    'name' => 'Niger',
                    'phoneCode' => '+227',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_niger'
            ],
            [
                'fields' => [
                    'name' => 'Nigeria',
                    'phoneCode' => '+234',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'NG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ngn'),
                ],
                'reference' => 'country_nigeria'
            ],
            [
                'fields' => [
                    'name' => 'Niue',
                    'phoneCode' => '+683',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nzd'),
                ],
                'reference' => 'country_niue'
            ],
            [
                'fields' => [
                    'name' => 'Norfolk Island',
                    'phoneCode' => '+672',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_norfolk_island'
            ],
            [
                'fields' => [
                    'name' => 'North Korea',
                    'phoneCode' => '+850',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KP',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_kpw'),
                ],
                'reference' => 'country_north_korea'
            ],
            [
                'fields' => [
                    'name' => 'Northern Mariana Islands',
                    'phoneCode' => '+1 670',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'MP',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_northern_mariana_islands'
            ],
            [
                'fields' => [
                    'name' => 'Norway',
                    'phoneCode' => '+47',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'NO',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_nok'),
                ],
                'reference' => 'country_norway'
            ],
            [
                'fields' => [
                    'name' => 'Oman',
                    'phoneCode' => '+968',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'OM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_omr'),
                ],
                'reference' => 'country_oman'
            ],
            [
                'fields' => [
                    'name' => 'Pakistan',
                    'phoneCode' => '+92',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PK',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_pkr'),
                ],
                'reference' => 'country_pakistan'
            ],
            [
                'fields' => [
                    'name' => 'Palau',
                    'phoneCode' => '+680',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_palau'
            ],
            [
                'fields' => [
                    'name' => 'Palestinian Territory',
                    'phoneCode' => '+970',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ils'),
                ],
                'reference' => 'country_palestinian_territory'
            ],
            [
                'fields' => [
                    'name' => 'Panama',
                    'phoneCode' => '+507',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_pab'),
                ],
                'reference' => 'country_panama'
            ],
            [
                'fields' => [
                    'name' => 'Papua New Guinea',
                    'phoneCode' => '+675',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_pgk'),
                ],
                'reference' => 'country_papua_new_guinea'
            ],
            [
                'fields' => [
                    'name' => 'Paraguay',
                    'phoneCode' => '+595',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_pyg'),
                ],
                'reference' => 'country_paraguay'
            ],
            [
                'fields' => [
                    'name' => 'Peru',
                    'phoneCode' => '+51',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_pen'),
                ],
                'reference' => 'country_peru'
            ],
            [
                'fields' => [
                    'name' => 'Philippines',
                    'phoneCode' => '+63',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PH',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_php'),
                ],
                'reference' => 'country_philippines'
            ],
            [
                'fields' => [
                    'name' => 'Poland',
                    'phoneCode' => '+48',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PL',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_pln'),
                ],
                'reference' => 'country_poland'
            ],
            [
                'fields' => [
                    'name' => 'Portugal',
                    'phoneCode' => '+351',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PT',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_portugal'
            ],
            [
                'fields' => [
                    'name' => 'Puerto Rico',
                    'phoneCode' => '+1 787',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'PR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_puerto_rico'
            ],
            [
                'fields' => [
                    'name' => 'Qatar',
                    'phoneCode' => '+974',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'QA',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_qar'),
                ],
                'reference' => 'country_qatar'
            ],
            [
                'fields' => [
                    'name' => 'Reunion',
                    'phoneCode' => '+262',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'RE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_reunion'
            ],
            [
                'fields' => [
                    'name' => 'Romania',
                    'phoneCode' => '+40',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'RO',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_ron'),
                ],
                'reference' => 'country_romania'
            ],
            [
                'fields' => [
                    'name' => 'Russia',
                    'phoneCode' => '+7',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'RU',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_rub'),
                ],
                'reference' => 'country_russia'
            ],
            [
                'fields' => [
                    'name' => 'Rwanda',
                    'phoneCode' => '+250',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'RW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_rwf'),
                ],
                'reference' => 'country_rwanda'
            ],
            [
                'fields' => [
                    'name' => 'Samoa',
                    'phoneCode' => '+685',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'WS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_wst'),
                ],
                'reference' => 'country_samoa'
            ],
            [
                'fields' => [
                    'name' => 'San Marino',
                    'phoneCode' => '+378',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_san_marino'
            ],
            [
                'fields' => [
                    'name' => 'Saudi Arabia',
                    'phoneCode' => '+966',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'SA',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_sar'),
                ],
                'reference' => 'country_saudi_arabia'
            ],
            [
                'fields' => [
                    'name' => 'Senegal',
                    'phoneCode' => '+221',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_senegal'
            ],
            [
                'fields' => [
                    'name' => 'Serbia',
                    'phoneCode' => '+381',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'RS',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_rsd'),
                ],
                'reference' => 'country_serbia'
            ],
            [
                'fields' => [
                    'name' => 'Seychelles',
                    'phoneCode' => '+248',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_scr'),
                ],
                'reference' => 'country_seychelles'
            ],
            [
                'fields' => [
                    'name' => 'Sierra Leone',
                    'phoneCode' => '+232',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SL',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_sll'),
                ],
                'reference' => 'country_sierra_leone'
            ],
            [
                'fields' => [
                    'name' => 'Singapore',
                    'phoneCode' => '+65',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_sgd'),
                ],
                'reference' => 'country_singapore'
            ],
            [
                'fields' => [
                    'name' => 'Slovakia',
                    'phoneCode' => '+421',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SK',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_slovakia'
            ],
            [
                'fields' => [
                    'name' => 'Slovenia',
                    'phoneCode' => '+386',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SI',
                    'dateFormat' => $this->getReference('date_format_7'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_slovenia'
            ],
            [
                'fields' => [
                    'name' => 'Solomon Islands',
                    'phoneCode' => '+677',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SB',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_sbd'),
                ],
                'reference' => 'country_solomon_islands'
            ],
            [
                'fields' => [
                    'name' => 'South Africa',
                    'phoneCode' => '+27',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SA',
                    'dateFormat' => $this->getReference('date_format_11'),
                    'currency' => $this->getReference('currency_sar'),
                ],
                'reference' => 'country_south_africa'
            ],
            [
                'fields' => [
                    'name' => 'South Georgia and the South Sandwich Islands',
                    'phoneCode' => '+500',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GS',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_gbp'),
                ],
                'reference' => 'country_south_georgia_and_the_south_sandwich_islands'
            ],
            [
                'fields' => [
                    'name' => 'South Korea',
                    'phoneCode' => '+82',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'KR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_krw'),
                ],
                'reference' => 'country_south_korea'
            ],
            [
                'fields' => [
                    'name' => 'Spain',
                    'phoneCode' => '+34',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ES',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_eur'),
                ],
                'reference' => 'country_spain'
            ],
            [
                'fields' => [
                    'name' => 'Sri Lanka',
                    'phoneCode' => '+94',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'LK',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_lkr'),
                ],
                'reference' => 'country_sri_lanka'
            ],
            [
                'fields' => [
                    'name' => 'Sudan',
                    'phoneCode' => '+249',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'SD',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_sdg'),
                ],
                'reference' => 'country_sudan'
            ],
            [
                'fields' => [
                    'name' => 'Suriname',
                    'phoneCode' => '+597',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_srg'),
                ],
                'reference' => 'country_suriname'
            ],
            [
                'fields' => [
                    'name' => 'Swaziland',
                    'phoneCode' => '+268',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SZ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_szl'),
                ],
                'reference' => 'country_swaziland'
            ],
            [
                'fields' => [
                    'name' => 'Sweden',
                    'phoneCode' => '+46',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'SE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_sek'),
                ],
                'reference' => 'country_sweden'
            ],
            [
                'fields' => [
                    'name' => 'Switzerland',
                    'phoneCode' => '+41',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'CH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_chf'),
                ],
                'reference' => 'country_switzerland'
            ],
            [
                'fields' => [
                    'name' => 'Syria',
                    'phoneCode' => '+963',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'SY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_syp'),
                ],
                'reference' => 'country_syria'
            ],
            [
                'fields' => [
                    'name' => 'Taiwan',
                    'phoneCode' => '+886',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_twd'),
                ],
                'reference' => 'country_taiwan'
            ],
            [
                'fields' => [
                    'name' => 'Tajikistan',
                    'phoneCode' => '+992',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TJ',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_tjs'),
                ],
                'reference' => 'country_tajikistan'
            ],
            [
                'fields' => [
                    'name' => 'Tanzania',
                    'phoneCode' => '+255',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TZ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_tzs'),
                ],
                'reference' => 'country_tanzania'
            ],
            [
                'fields' => [
                    'name' => 'Thailand',
                    'phoneCode' => '+66',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TH',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_thb'),
                ],
                'reference' => 'country_thailand'
            ],
            [
                'fields' => [
                    'name' => 'Timor Leste',
                    'phoneCode' => '+670',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TL',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_timor_leste'
            ],
            [
                'fields' => [
                    'name' => 'Togo',
                    'phoneCode' => '+228',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xof'),
                ],
                'reference' => 'country_togo'
            ],
            [
                'fields' => [
                    'name' => 'Tokelau',
                    'phoneCode' => '+690',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TK',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_nzd'),
                ],
                'reference' => 'country_tokelau'
            ],
            [
                'fields' => [
                    'name' => 'Tonga',
                    'phoneCode' => '+676',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TO',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_top'),
                ],
                'reference' => 'country_tonga'
            ],
            [
                'fields' => [
                    'name' => 'Trinidad and Tobago',
                    'phoneCode' => '+1 868',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TT',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ttd'),
                ],
                'reference' => 'country_trinidad_and_tobago'
            ],
            [
                'fields' => [
                    'name' => 'Tunisia',
                    'phoneCode' => '+216',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_tnd'),
                ],
                'reference' => 'country_tunisia'
            ],
            [
                'fields' => [
                    'name' => 'Turkey',
                    'phoneCode' => '+90',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TR',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_try'),
                ],
                'reference' => 'country_turkey'
            ],
            [
                'fields' => [
                    'name' => 'Turkmenistan',
                    'phoneCode' => '+993',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TM',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_tmt'),
                ],
                'reference' => 'country_turkmenistan'
            ],
            [
                'fields' => [
                    'name' => 'Turks and Caicos Islands',
                    'phoneCode' => '+1 649',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TC',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_turks_and_caicos_islands'
            ],
            [
                'fields' => [
                    'name' => 'Tuvalu',
                    'phoneCode' => '+688',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'TV',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aud'),
                ],
                'reference' => 'country_tuvalu'
            ],
            [
                'fields' => [
                    'name' => 'U.S. Virgin Islands',
                    'phoneCode' => '+1 340',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'VI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_u.s._virgin_islands'
            ],
            [
                'fields' => [
                    'name' => 'Uganda',
                    'phoneCode' => '+256',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'UG',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_ugx'),
                ],
                'reference' => 'country_uganda'
            ],
            [
                'fields' => [
                    'name' => 'Ukraine',
                    'phoneCode' => '+380',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'UA',
                    'dateFormat' => $this->getReference('date_format_13'),
                    'currency' => $this->getReference('currency_uah'),
                ],
                'reference' => 'country_ukraine'
            ],
            [
                'fields' => [
                    'name' => 'United Arab Emirates',
                    'phoneCode' => '+971',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'AE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_aed'),
                ],
                'reference' => 'country_united_arab_emirates'
            ],
            [
                'fields' => [
                    'name' => 'United Kingdom',
                    'phoneCode' => '+44',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'GB',
                    'dateFormat' => $this->getReference('date_format_3'),
                    'currency' => $this->getReference('currency_gbp'),
                ],
                'reference' => 'country_united_kingdom'
            ],
            [
                'fields' => [
                    'name' => 'United States',
                    'phoneCode' => '+1',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'US',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_united_states'
            ],
            [
                'fields' => [
                    'name' => 'Uruguay',
                    'phoneCode' => '+598',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'UY',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_uyu'),
                ],
                'reference' => 'country_uruguay'
            ],
            [
                'fields' => [
                    'name' => 'Uzbekistan',
                    'phoneCode' => '+998',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'UZ',
                    'dateFormat' => $this->getReference('date_format_1'),
                    'currency' => $this->getReference('currency_uzs'),
                ],
                'reference' => 'country_uzbekistan'
            ],
            [
                'fields' => [
                    'name' => 'Vanuatu',
                    'phoneCode' => '+678',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'VU',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_vuv'),
                ],
                'reference' => 'country_vanuatu'
            ],
            [
                'fields' => [
                    'name' => 'Venezuela',
                    'phoneCode' => '+58',
                    'isState' => 1,
                    'workdayStart' => '1',
                    'countryCode' => 'VE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_vef'),
                ],
                'reference' => 'country_venezuela'
            ],
            [
                'fields' => [
                    'name' => 'Vietnam',
                    'phoneCode' => '+84',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'VN',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_vnd'),
                ],
                'reference' => 'country_vietnam'
            ],
            [
                'fields' => [
                    'name' => 'Wake Island',
                    'phoneCode' => '+1 808',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'WI',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_usd'),
                ],
                'reference' => 'country_wake_island'
            ],
            [
                'fields' => [
                    'name' => 'Wallis and Futuna',
                    'phoneCode' => '+681',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'WF',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_xpf'),
                ],
                'reference' => 'country_wallis_and_futuna'
            ],
            [
                'fields' => [
                    'name' => 'Yemen',
                    'phoneCode' => '+967',
                    'isState' => 0,
                    'workdayStart' => '7',
                    'countryCode' => 'YE',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_yer'),
                ],
                'reference' => 'country_yemen'
            ],
            [
                'fields' => [
                    'name' => 'Zambia',
                    'phoneCode' => '+260',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ZM',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_zmk'),
                ],
                'reference' => 'country_zambia'
            ],
            [
                'fields' => [
                    'name' => 'Zanzibar',
                    'phoneCode' => '+255',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ZZ',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_tzs'),
                ],
                'reference' => 'country_zanzibar'
            ],
            [
                'fields' => [
                    'name' => 'Zimbabwe',
                    'phoneCode' => '+263',
                    'isState' => 0,
                    'workdayStart' => '1',
                    'countryCode' => 'ZW',
                    'dateFormat' => $this->getReference('date_format_6'),
                    'currency' => $this->getReference('currency_zwl'),
                ],
                'reference' => 'country_zimbabwe'
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], Country::class);
            $manager->persist($entity);
            if (array_key_exists('reference', $itemData)) {
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
        return 15;
    }

}