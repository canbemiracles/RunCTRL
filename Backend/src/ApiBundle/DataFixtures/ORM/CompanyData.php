<?php


use ApiBundle\DataFixtures\Traits\FillEntityTrait;
use ApiBundle\Entity\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompanyData  extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
                        'name' => 'Simple company',
                        'geographicalArea' => $this->getReference('geo_area_spain'),
                        'weekStartOn' => 1,
                        'overtimeHourlyRate' => 0.15,
                        'weekendRate' => 1.15,
                        'timeZone' => $this->getReference('time_zone_2'),
                        'currency' => $this->getReference('currency_usd'),
                        'dateFormat' => $this->getReference('date_format_1'),
                        'subcategory' => $this->getReference('subcategory_1'),
                    ],
                'reference' => 'company_simple_company'
            ],
        ];

        foreach ($data as $itemData) {
            $entity = $this->fillEntityFromArray($itemData['fields'], Company::class);
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
        return 50;
    }

}
