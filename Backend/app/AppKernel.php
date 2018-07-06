<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //Application Bundles
            new AppBundle\AppBundle(),
            new ApiBundle\ApiBundle(),
            new AssignmentsBundle\AssignmentsBundle(),
            new WebSocketsBundle\WebSocketsBundle(),

            //FOS Bundles
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),

            //Migrations and fixtures
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            //Serializer
            new JMS\SerializerBundle\JMSSerializerBundle(),

            //Api Doc
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            //Doctrine extensions
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            //QR Bundle
            new Endroid\QrCode\Bundle\QrCodeBundle\EndroidQrCodeBundle(),

            //GeoIP Bundle (https://github.com/IDCI-Consulting/Maxmind-GeoIp)
            new Maxmind\Bundle\GeoipBundle\MaxmindGeoipBundle(),

            //Cors Bundle (https://github.com/nelmio/NelmioCorsBundle)
            new Nelmio\CorsBundle\NelmioCorsBundle(),

            //WebSockets (https://github.com/GeniusesOfSymfony/WebSocketBundle)
            new Gos\Bundle\WebSocketBundle\GosWebSocketBundle(),
            new Gos\Bundle\PubSubRouterBundle\GosPubSubRouterBundle(),

            //Pusher for iOS and Android
            new RedjanYm\FCMBundle\RedjanYmFCMBundle(),

            //Elastic search
            new FOS\ElasticaBundle\FOSElasticaBundle(),

            //KNP Paginator
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            //KNP Snappy
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),

            // Payum
            new Payum\Bundle\PayumBundle\PayumBundle(),

            // Cloudflare
            new Gpenverne\CloudflareBundle\CloudflareBundle(),

            //SONATA
            // These are the other bundles the SonataAdminBundle relies on
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            // two-factor authentication
            new Scheb\TwoFactorBundle\SchebTwoFactorBundle(),

            new RMS\PushNotificationsBundle\RMSPushNotificationsBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
