<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class EntityManagerStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class EntityManagerStub implements EntityManagerInterface
{
    /**
     * @var array
     */
    protected $entities = [];

    /**
     * @var array|RepositoryStub[]
     */
    protected $repositories = [];

    /**
     * @var integer
     */
    public $flushCount = 0;

    /**
     * @var array
     */
    public $deleted = [];

    /**
     * @var array
     */
    public $persisted = [];

    /**
     * @var bool
     */
    public $hasBeginTransaction = false;

    /**
     * @var bool
     */
    public $hasCommit = false;

    /**
     * @var bool
     */
    public $hasRollback = false;

    /**
     * @return void
     */
    public function flush()
    {
        $this->flushCount++;
    }

    /**
     * @inheritDoc
     */
    public function persist($object)
    {
        $class = \get_class($object);
        $this->persisted[$class] = $this->persisted[$class] ?? [];
        $this->persisted[$class][] = $object;
    }

    /**
     * @inheritDoc
     */
    public function remove($object)
    {
        $class = \get_class($object);
        $this->deleted[$class] = $this->deleted[$class] ?? [];
        $this->deleted[$class][] = $object;
    }

    /**
     * @inheritDoc
     */
    public function getRepository($className)
    {
        $this->repositories[$className] = $this->repositories[$className] ?? new RepositoryStub($className, $this);

        return $this->repositories[$className];
    }

    /**
     * @param RepositoryStub $repositoryStub
     *
     * @return $this
     */
    public function setRepository(RepositoryStub $repositoryStub): self
    {
        $this->repositories[$repositoryStub->getClassName()] = $repositoryStub;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function find($className, $id)
    {
        return $this->getRepository($className)->find($id);
    }

    /**
     * @param object $entity
     *
     * @return self
     */
    public function addEntity(object $entity): self
    {
        $class = \get_class($entity);
        $this->entities[$class] = $this->entities[$class] ?? [];
        $this->entities[$class][] = $entity;

        return $this;
    }

    /**
     * @param string $classname
     *
     * @return array
     */
    public function getEntities(string $classname): array
    {
        return $this->entities[$classname] ?? [];
    }

    /** Other functions required for the interface */

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getCache()
    {
        // TODO: Implement getCache() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getExpressionBuilder()
    {
        // TODO: Implement getExpressionBuilder() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function beginTransaction()
    {
        $this->hasBeginTransaction = true;
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function transactional($func)
    {
        // TODO: Implement transactional() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function commit()
    {
        $this->hasCommit = true;
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function rollback()
    {
        $this->hasRollback = true;
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function createQuery($dql = '')
    {
        // TODO: Implement createQuery() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function createNamedQuery($name)
    {
        // TODO: Implement createNamedQuery() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function createNativeQuery($sql, ResultSetMapping $rsm)
    {
        // TODO: Implement createNativeQuery() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function createNamedNativeQuery($name)
    {
        // TODO: Implement createNamedNativeQuery() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function createQueryBuilder()
    {
        // TODO: Implement createQueryBuilder() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getReference($entityName, $id)
    {
        // TODO: Implement getReference() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getPartialReference($entityName, $identifier)
    {
        // TODO: Implement getPartialReference() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function copy($entity, $deep = false)
    {
        // TODO: Implement copy() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function lock($entity, $lockMode, $lockVersion = null)
    {
        // TODO: Implement lock() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getEventManager()
    {
        // TODO: Implement getEventManager() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getConfiguration()
    {
        // TODO: Implement getConfiguration() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function isOpen()
    {
        // TODO: Implement isOpen() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getUnitOfWork()
    {
        // TODO: Implement getUnitOfWork() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getHydrator($hydrationMode)
    {
        // TODO: Implement getHydrator() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function newHydrator($hydrationMode)
    {
        // TODO: Implement newHydrator() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getProxyFactory()
    {
        // TODO: Implement getProxyFactory() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getFilters()
    {
        // TODO: Implement getFilters() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function isFiltersStateClean()
    {
        // TODO: Implement isFiltersStateClean() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function hasFilters()
    {
        // TODO: Implement hasFilters() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function merge($object)
    {
        // TODO: Implement merge() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function clear($objectName = null)
    {
        // TODO: Implement clear() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function detach($object)
    {
        // TODO: Implement detach() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function refresh($object)
    {
        // TODO: Implement refresh() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getMetadataFactory()
    {
        // TODO: Implement getMetadataFactory() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function initializeObject($obj)
    {
        // TODO: Implement initializeObject() method.
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function contains($object)
    {
        // TODO: Implement contains() method.
    }

    /**
     * @inheritDoc
     */
    public function getClassMetadata($className): Mapping\ClassMetadata
    {
        return new Mapping\ClassMetadata($className);
    }
}
