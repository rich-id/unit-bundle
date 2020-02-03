<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests;

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle;
use Liip\FunctionalTestBundle\LiipFunctionalTestBundle;
use Liip\TestFixturesBundle\LiipTestFixturesBundle;
use RichCongress\Bundle\UnitBundle\RichCongressUnitBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Class AppKernel
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class AppKernel extends Kernel
{
    /**
     * AppKernel constructor.
     *
     * @param string  $environment
     * @param boolean $debug
     */
    public function __construct(string $environment, bool $debug)
    {
        parent::__construct('unit_bundle_test', false);
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return iterable|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles(): array
    {
        $bundles[] = new DoctrineBundle();
        $bundles[] = new DoctrineFixturesBundle();
        $bundles[] = new FrameworkBundle();
        $bundles[] = new LiipFunctionalTestBundle();
        $bundles[] = new LiipTestFixturesBundle();
        $bundles[] = new RichCongressUnitBundle();
        $bundles[] = new SecurityBundle();
        $bundles[] = new EightPointsGuzzleBundle();
        $bundles[] = new DAMADoctrineTestBundle();

        return $bundles;
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return __DIR__ . '/../../../';
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
        $loader->load(__DIR__ . '/services.yml');
    }
}
