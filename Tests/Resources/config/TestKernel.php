<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests;

use RichCongress\Bundle\UnitBundle\Kernel\DefaultTestKernel;

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
