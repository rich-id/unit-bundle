<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Stubs;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Stubs\ContainerStub;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Command\DummyCommand;

/**
 * Class ContainerStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Stubs\ContainerStub
 */
class ContainerStubTest extends TestCase
{
    /**
     * @var ContainerStub
     */
    protected $container;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->container = new ContainerStub();
    }

    /**
     * @return void
     */
    public function testSetHasGetInitialized(): void
    {
        self::assertFalse($this->container->has('service'));
        self::assertFalse($this->container->initialized('service'));

        $service = new DummyCommand();
        $this->container->set('service', $service);

        self::assertTrue($this->container->has('service'));
        self::assertTrue($this->container->initialized('service'));
        self::assertSame($service, $this->container->get('service'));
    }

    /**
     * @return void
     */
    public function testGetHasSetParameter(): void
    {
        self::assertFalse($this->container->hasParameter('parameter'));

        $this->container->setParameter('parameter', 'value');

        self::assertTrue($this->container->hasParameter('parameter'));
        self::assertSame('value', $this->container->getParameter('parameter'));
    }
}
