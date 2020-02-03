<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Stubs;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Stubs\EventDispatcherStub;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Event\DummyEvent;

/**
 * Class EventDispatcherStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Stubs\EventDispatcherStub
 */
class EventDispatcherStubTest extends TestCase
{
    /**
     * @var EventDispatcherStub
     */
    protected $eventDispatcher;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = new EventDispatcherStub();
    }

    /**
     * @return void
     */
    public function testDispatchReversed(): void
    {
        $event = new DummyEvent();

        $this->eventDispatcher->dispatch('event', $event);

        self::assertContains($event, $this->eventDispatcher->events);
    }
}
