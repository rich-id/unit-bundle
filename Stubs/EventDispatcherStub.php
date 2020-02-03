<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EventDispatcherStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class EventDispatcherStub implements EventDispatcherInterface
{
    /**
     * @var array
     */
    public $events = [];

    /**
     * @param object|string        $event
     * @param object|string|null   $eventName
     *
     * @return object
     */
    public function dispatch($event, $eventName = null): object
    {
        if (\is_string($event) && \is_object($eventName)) {
            return $this->dispatch($eventName, $event);
        }

        $this->events[] = $event;

        return $event;
    }

    /** Other functions required for the interface */

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        // TODO: Implement addListener() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        // TODO: Implement addSubscriber() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function removeListener($eventName, $listener)
    {
        // TODO: Implement removeListener() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        // TODO: Implement removeSubscriber() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getListeners($eventName = null)
    {
        // TODO: Implement getListeners() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getListenerPriority($eventName, $listener)
    {
        // TODO: Implement getListenerPriority() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function hasListeners($eventName = null)
    {
        // TODO: Implement hasListeners() method.
    }
}
