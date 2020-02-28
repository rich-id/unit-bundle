<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests;

use EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle;
use RichCongress\Bundle\UnitBundle\Kernel\DefaultTestKernel;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class TestKernel
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class TestKernel extends DefaultTestKernel
{
    /**
     * TestKernel constructor.
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
     * @return array|BundleInterface[] An iterable of bundle instances
     */
    public function registerBundles(): array
    {
        $bundles = parent::registerBundles();
        $bundles[] = new SecurityBundle();
        $bundles[] = new EightPointsGuzzleBundle();

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
     * @return string|null
     */
    public function getConfigurationDir(): ?string
    {
        return $this->getProjectDir() . '/Tests/Resources/config';
    }
}
