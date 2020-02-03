<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Class ContainerStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ContainerStub implements ContainerInterface
{
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var ParameterBag
     */
    protected $parameterBag;

    /**
     * ContainerStub constructor.
     */
    public function __construct()
    {
        $this->parameterBag = new ParameterBag();
    }

    /**
     * @param string      $id
     * @param object|null $service
     *
     * @return void
     */
    public function set($id, $service)
    {
        $this->services[$id] = $service;
    }

    /**
     * @param string $id
     * @param int    $invalidBehavior
     *
     * @return mixed
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return $this->services[$id] ?? null;
    }

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function initialized($id): bool
    {
        return $this->has($id);
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getParameter($name)
    {
        return $this->parameterBag->get($name);
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasParameter($name): bool
    {
        return $this->parameterBag->has($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setParameter($name, $value): void
    {
        $this->parameterBag->set($name, $value);
    }
}
