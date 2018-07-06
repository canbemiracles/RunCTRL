<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CurrencyData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                    'currency' => 'usd',
                ],
                'reference' => 'currency_usd'
            ],
            [
                'fields' => [
                    'currency' => 'cad',
                ],
                'reference' => 'currency_cad'
            ],
            [
                'fields' => [
                    'currency' => 'eur',
                ],
                'reference' => 'currency_eur'
            ],
            [
                'fields' => [
                    'currency' => 'aed',
                ],
                'reference' => 'currency_aed'
            ],
            [
                'fields' => [
                    'currency' => 'afn',
                ],
                'reference' => 'currency_afn'
            ],
            [
                'fields' => [
                    'currency' => 'all',
                ],
                'reference' => 'currency_all'
            ],
            [
                'fields' => [
                    'currency' => 'amd',
                ],
                'reference' => 'currency_amd'
            ],
            [
                'fields' => [
                    'currency' => 'ars',
                ],
                'reference' => 'currency_ars'
            ],
            [
                'fields' => [
                    'currency' => 'aud',
                ],
                'reference' => 'currency_aud'
            ],
            [
                'fields' => [
                    'currency' => 'azn',
                ],
                'reference' => 'currency_azn'
            ],
            [
                'fields' => [
                    'currency' => 'bam',
                ],
                'reference' => 'currency_bam'
            ],
            [
                'fields' => [
                    'currency' => 'bdt',
                ],
                'reference' => 'currency_bdt'
            ],
            [
                'fields' => [
                    'currency' => 'bgn',
                ],
                'reference' => 'currency_bgn'
            ],
            [
                'fields' => [
                    'currency' => 'bhd',
                ],
                'reference' => 'currency_bhd'
            ],
            [
                'fields' => [
                    'currency' => 'bif',
                ],
                'reference' => 'currency_bif'
            ],
            [
                'fields' => [
                    'currency' => 'bnd',
                ],
                'reference' => 'currency_bnd'
            ],
            [
                'fields' => [
                    'currency' => 'bob',
                ],
                'reference' => 'currency_bob'
            ],
            [
                'fields' => [
                    'currency' => 'brl',
                ],
                'reference' => 'currency_brl'
            ],
            [
                'fields' => [
                    'currency' => 'bwp',
                ],
                'reference' => 'currency_bwp'
            ],
            [
                'fields' => [
                    'currency' => 'byr',
                ],
                'reference' => 'currency_byr'
            ],
            [
                'fields' => [
                    'currency' => 'bzd',
                ],
                'reference' => 'currency_bzd'
            ],
            [
                'fields' => [
                    'currency' => 'cdf',
                ],
                'reference' => 'currency_cdf'
            ],
            [
                'fields' => [
                    'currency' => 'chf',
                ],
                'reference' => 'currency_chf'
            ],
            [
                'fields' => [
                    'currency' => 'clp',
                ],
                'reference' => 'currency_clp'
            ],
            [
                'fields' => [
                    'currency' => 'cny',
                ],
                'reference' => 'currency_cny'
            ],
            [
                'fields' => [
                    'currency' => 'cop',
                ],
                'reference' => 'currency_cop'
            ],
            [
                'fields' => [
                    'currency' => 'crc',
                ],
                'reference' => 'currency_crc'
            ],
            [
                'fields' => [
                    'currency' => 'cve',
                ],
                'reference' => 'currency_cve'
            ],
            [
                'fields' => [
                    'currency' => 'czk',
                ],
                'reference' => 'currency_czk'
            ],
            [
                'fields' => [
                    'currency' => 'djf',
                ],
                'reference' => 'currency_djf'
            ],
            [
                'fields' => [
                    'currency' => 'dkk',
                ],
                'reference' => 'currency_dkk'
            ],
            [
                'fields' => [
                    'currency' => 'dop',
                ],
                'reference' => 'currency_dop'
            ],
            [
                'fields' => [
                    'currency' => 'dzd',
                ],
                'reference' => 'currency_dzd'
            ],
            [
                'fields' => [
                    'currency' => 'eek',
                ],
                'reference' => 'currency_eek'
            ],
            [
                'fields' => [
                    'currency' => 'egp',
                ],
                'reference' => 'currency_egp'
            ],
            [
                'fields' => [
                    'currency' => 'ern',
                ],
                'reference' => 'currency_ern'
            ],
            [
                'fields' => [
                    'currency' => 'etb',
                ],
                'reference' => 'currency_etb'
            ],
            [
                'fields' => [
                    'currency' => 'gbp',
                ],
                'reference' => 'currency_gbp'
            ],
            [
                'fields' => [
                    'currency' => 'gel',
                ],
                'reference' => 'currency_gel'
            ],
            [
                'fields' => [
                    'currency' => 'ghs',
                ],
                'reference' => 'currency_ghs'
            ],
            [
                'fields' => [
                    'currency' => 'gnf',
                ],
                'reference' => 'currency_gnf'
            ],
            [
                'fields' => [
                    'currency' => 'gtq',
                ],
                'reference' => 'currency_gtq'
            ],
            [
                'fields' => [
                    'currency' => 'hkd',
                ],
                'reference' => 'currency_hkd'
            ],
            [
                'fields' => [
                    'currency' => 'hnl',
                ],
                'reference' => 'currency_hnl'
            ],
            [
                'fields' => [
                    'currency' => 'hrk',
                ],
                'reference' => 'currency_hrk'
            ],
            [
                'fields' => [
                    'currency' => 'huf',
                ],
                'reference' => 'currency_huf'
            ],
            [
                'fields' => [
                    'currency' => 'idr',
                ],
                'reference' => 'currency_idr'
            ],
            [
                'fields' => [
                    'currency' => 'ils',
                ],
                'reference' => 'currency_ils'
            ],
            [
                'fields' => [
                    'currency' => 'inr',
                ],
                'reference' => 'currency_inr'
            ],
            [
                'fields' => [
                    'currency' => 'iqd',
                ],
                'reference' => 'currency_iqd'
            ],
            [
                'fields' => [
                    'currency' => 'irr',
                ],
                'reference' => 'currency_irr'
            ],
            [
                'fields' => [
                    'currency' => 'isk',
                ],
                'reference' => 'currency_isk'
            ],
            [
                'fields' => [
                    'currency' => 'jmd',
                ],
                'reference' => 'currency_jmd'
            ],
            [
                'fields' => [
                    'currency' => 'jod',
                ],
                'reference' => 'currency_jod'
            ],
            [
                'fields' => [
                    'currency' => 'jpy',
                ],
                'reference' => 'currency_jpy'
            ],
            [
                'fields' => [
                    'currency' => 'kes',
                ],
                'reference' => 'currency_kes'
            ],
            [
                'fields' => [
                    'currency' => 'khr',
                ],
                'reference' => 'currency_khr'
            ],
            [
                'fields' => [
                    'currency' => 'kmf',
                ],
                'reference' => 'currency_kmf'
            ],
            [
                'fields' => [
                    'currency' => 'krw',
                ],
                'reference' => 'currency_krw'
            ],
            [
                'fields' => [
                    'currency' => 'kwd',
                ],
                'reference' => 'currency_kwd'
            ],
            [
                'fields' => [
                    'currency' => 'kzt',
                ],
                'reference' => 'currency_kzt'
            ],
            [
                'fields' => [
                    'currency' => 'lbp',
                ],
                'reference' => 'currency_lbp'
            ],
            [
                'fields' => [
                    'currency' => 'lkr',
                ],
                'reference' => 'currency_lkr'
            ],
            [
                'fields' => [
                    'currency' => 'ltl',
                ],
                'reference' => 'currency_ltl'
            ],
            [
                'fields' => [
                    'currency' => 'lvl',
                ],
                'reference' => 'currency_lvl'
            ],
            [
                'fields' => [
                    'currency' => 'lyd',
                ],
                'reference' => 'currency_lyd'
            ],
            [
                'fields' => [
                    'currency' => 'mad',
                ],
                'reference' => 'currency_mad'
            ],
            [
                'fields' => [
                    'currency' => 'mdl',
                ],
                'reference' => 'currency_mdl'
            ],
            [
                'fields' => [
                    'currency' => 'mga',
                ],
                'reference' => 'currency_mga'
            ],
            [
                'fields' => [
                    'currency' => 'mkd',
                ],
                'reference' => 'currency_mkd'
            ],
            [
                'fields' => [
                    'currency' => 'mmk',
                ],
                'reference' => 'currency_mmk'
            ],
            [
                'fields' => [
                    'currency' => 'mop',
                ],
                'reference' => 'currency_mop'
            ],
            [
                'fields' => [
                    'currency' => 'mur',
                ],
                'reference' => 'currency_mur'
            ],
            [
                'fields' => [
                    'currency' => 'mxn',
                ],
                'reference' => 'currency_mxn'
            ],
            [
                'fields' => [
                    'currency' => 'myr',
                ],
                'reference' => 'currency_myr'
            ],
            [
                'fields' => [
                    'currency' => 'mzn',
                ],
                'reference' => 'currency_mzn'
            ],
            [
                'fields' => [
                    'currency' => 'nad',
                ],
                'reference' => 'currency_nad'
            ],
            [
                'fields' => [
                    'currency' => 'ngn',
                ],
                'reference' => 'currency_ngn'
            ],
            [
                'fields' => [
                    'currency' => 'nio',
                ],
                'reference' => 'currency_nio'
            ],
            [
                'fields' => [
                    'currency' => 'nok',
                ],
                'reference' => 'currency_nok'
            ],
            [
                'fields' => [
                    'currency' => 'npr',
                ],
                'reference' => 'currency_npr'
            ],
            [
                'fields' => [
                    'currency' => 'nzd',
                ],
                'reference' => 'currency_nzd'
            ],
            [
                'fields' => [
                    'currency' => 'omr',
                ],
                'reference' => 'currency_omr'
            ],
            [
                'fields' => [
                    'currency' => 'pab',
                ],
                'reference' => 'currency_pab'
            ],
            [
                'fields' => [
                    'currency' => 'pen',
                ],
                'reference' => 'currency_pen'
            ],
            [
                'fields' => [
                    'currency' => 'php',
                ],
                'reference' => 'currency_php'
            ],
            [
                'fields' => [
                    'currency' => 'pkr',
                ],
                'reference' => 'currency_pkr'
            ],
            [
                'fields' => [
                    'currency' => 'pln',
                ],
                'reference' => 'currency_pln'
            ],
            [
                'fields' => [
                    'currency' => 'pyg',
                ],
                'reference' => 'currency_pyg'
            ],
            [
                'fields' => [
                    'currency' => 'qar',
                ],
                'reference' => 'currency_qar'
            ],
            [
                'fields' => [
                    'currency' => 'ron',
                ],
                'reference' => 'currency_ron'
            ],
            [
                'fields' => [
                    'currency' => 'rsd',
                ],
                'reference' => 'currency_rsd'
            ],
            [
                'fields' => [
                    'currency' => 'rub',
                ],
                'reference' => 'currency_rub'
            ],
            [
                'fields' => [
                    'currency' => 'rwf',
                ],
                'reference' => 'currency_rwf'
            ],
            [
                'fields' => [
                    'currency' => 'sar',
                ],
                'reference' => 'currency_sar'
            ],
            [
                'fields' => [
                    'currency' => 'sdg',
                ],
                'reference' => 'currency_sdg'
            ],
            [
                'fields' => [
                    'currency' => 'sek',
                ],
                'reference' => 'currency_sek'
            ],
            [
                'fields' => [
                    'currency' => 'sgd',
                ],
                'reference' => 'currency_sgd'
            ],
            [
                'fields' => [
                    'currency' => 'sos',
                ],
                'reference' => 'currency_sos'
            ],
            [
                'fields' => [
                    'currency' => 'syp',
                ],
                'reference' => 'currency_syp'
            ],
            [
                'fields' => [
                    'currency' => 'thb',
                ],
                'reference' => 'currency_thb'
            ],
            [
                'fields' => [
                    'currency' => 'tnd',
                ],
                'reference' => 'currency_tnd'
            ],
            [
                'fields' => [
                    'currency' => 'top',
                ],
                'reference' => 'currency_top'
            ],
            [
                'fields' => [
                    'currency' => 'try',
                ],
                'reference' => 'currency_try'
            ],
            [
                'fields' => [
                    'currency' => 'ttd',
                ],
                'reference' => 'currency_ttd'
            ],
            [
                'fields' => [
                    'currency' => 'twd',
                ],
                'reference' => 'currency_twd'
            ],
            [
                'fields' => [
                    'currency' => 'tzs',
                ],
                'reference' => 'currency_tzs'
            ],
            [
                'fields' => [
                    'currency' => 'uah',
                ],
                'reference' => 'currency_uah'
            ],
            [
                'fields' => [
                    'currency' => 'ugx',
                ],
                'reference' => 'currency_ugx'
            ],
            [
                'fields' => [
                    'currency' => 'uyu',
                ],
                'reference' => 'currency_uyu'
            ],
            [
                'fields' => [
                    'currency' => 'uzs',
                ],
                'reference' => 'currency_uzs'
            ],
            [
                'fields' => [
                    'currency' => 'vef',
                ],
                'reference' => 'currency_vef'
            ],
            [
                'fields' => [
                    'currency' => 'vnd',
                ],
                'reference' => 'currency_vnd'
            ],
            [
                'fields' => [
                    'currency' => 'xaf',
                ],
                'reference' => 'currency_xaf'
            ],
            [
                'fields' => [
                    'currency' => 'xof',
                ],
                'reference' => 'currency_xof'
            ],
            [
                'fields' => [
                    'currency' => 'yer',
                ],
                'reference' => 'currency_yer'
            ],
            [
                'fields' => [
                    'currency' => 'zar',
                ],
                'reference' => 'currency_zar'
            ],
            [
                'fields' => [
                    'currency' => 'zmk',
                ],
                'reference' => 'currency_zmk'
            ],
            [
                'fields' => [
                    'currency' => 'aoa',
                ],
                'reference' => 'currency_aoa'
            ],
            [
                'fields' => [
                    'currency' => 'xcd',
                ],
                'reference' => 'currency_xcd'
            ],
            [
                'fields' => [
                    'currency' => 'awg',
                ],
                'reference' => 'currency_awg'
            ],
            [
                'fields' => [
                    'currency' => 'bsd',
                ],
                'reference' => 'currency_bsd'
            ],
            [
                'fields' => [
                    'currency' => 'bbd',
                ],
                'reference' => 'currency_bbd'
            ],
            [
                'fields' => [
                    'currency' => 'bmd',
                ],
                'reference' => 'currency_bmd'
            ],
            [
                'fields' => [
                    'currency' => 'btn',
                ],
                'reference' => 'currency_btn'
            ],
            [
                'fields' => [
                    'currency' => 'kyd',
                ],
                'reference' => 'currency_kyd'
            ],
            [
                'fields' => [
                    'currency' => 'cup',
                ],
                'reference' => 'currency_cup'
            ],
            [
                'fields' => [
                    'currency' => 'ang',
                ],
                'reference' => 'currency_ang'
            ],
            [
                'fields' => [
                    'currency' => 'fjd',
                ],
                'reference' => 'currency_fjd'
            ],
            [
                'fields' => [
                    'currency' => 'gmd',
                ],
                'reference' => 'currency_gmd'
            ],
            [
                'fields' => [
                    'currency' => 'gip',
                ],
                'reference' => 'currency_gip'
            ],
            [
                'fields' => [
                    'currency' => 'gyd',
                ],
                'reference' => 'currency_gyd'
            ],
            [
                'fields' => [
                    'currency' => 'htg',
                ],
                'reference' => 'currency_htg'
            ],
            [
                'fields' => [
                    'currency' => 'kgs',
                ],
                'reference' => 'currency_kgs'
            ],
            [
                'fields' => [
                    'currency' => 'lak',
                ],
                'reference' => 'currency_lak'
            ],
            [
                'fields' => [
                    'currency' => 'lsl',
                ],
                'reference' => 'currency_lsl'
            ],
            [
                'fields' => [
                    'currency' => 'lrd',
                ],
                'reference' => 'currency_lrd'
            ],
            [
                'fields' => [
                    'currency' => 'mwk',
                ],
                'reference' => 'currency_mwk'
            ],
            [
                'fields' => [
                    'currency' => 'mvr',
                ],
                'reference' => 'currency_mvr'
            ],
            [
                'fields' => [
                    'currency' => 'mru',
                ],
                'reference' => 'currency_mru'
            ],
            [
                'fields' => [
                    'currency' => 'mnt',
                ],
                'reference' => 'currency_mnt'
            ],
            [
                'fields' => [
                    'currency' => 'xpf',
                ],
                'reference' => 'currency_xpf'
            ],
            [
                'fields' => [
                    'currency' => 'kpw',
                ],
                'reference' => 'currency_kpw'
            ],
            [
                'fields' => [
                    'currency' => 'pgk',
                ],
                'reference' => 'currency_pgk'
            ],
            [
                'fields' => [
                    'currency' => 'wst',
                ],
                'reference' => 'currency_wst'
            ],
            [
                'fields' => [
                    'currency' => 'scr',
                ],
                'reference' => 'currency_scr'
            ],
            [
                'fields' => [
                    'currency' => 'sll',
                ],
                'reference' => 'currency_sll'
            ],
            [
                'fields' => [
                    'currency' => 'sbd',
                ],
                'reference' => 'currency_sbd'
            ],
            [
                'fields' => [
                    'currency' => 'srg',
                ],
                'reference' => 'currency_srg'
            ],
            [
                'fields' => [
                    'currency' => 'szl',
                ],
                'reference' => 'currency_szl'
            ],
            [
                'fields' => [
                    'currency' => 'tjs',
                ],
                'reference' => 'currency_tjs'
            ],
            [
                'fields' => [
                    'currency' => 'tmt',
                ],
                'reference' => 'currency_tmt'
            ],
            [
                'fields' => [
                    'currency' => 'vuv',
                ],
                'reference' => 'currency_vuv'
            ],
            [
                'fields' => [
                    'currency' => 'zwl',
                ],
                'reference' => 'currency_zwl'
            ],

        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], Currency::class);
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
        return 13;
    }

}
