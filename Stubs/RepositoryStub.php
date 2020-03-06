<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class RepositoryStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class RepositoryStub implements ObjectRepository
{
    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var EntityManagerStub
     */
    protected $entityManager;

    /**
     * @var PropertyAccess
     */
    protected $propertyAccessor;

    /**
     * RepositoryStub constructor.
     *
     * @param string                 $entityClass
     * @param EntityManagerStub|null $entityManager
     */
    public function __construct(string $entityClass, EntityManagerStub $entityManager = null)
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->entityClass = $entityClass;
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $id
     *
     * @return object|null
     */
    public function find($id): ?object
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @return array|object[]
     */
    public function findAll(): array
    {
        return $this->getManager()->getEntities($this->entityClass);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array|object[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $entities = $this->findAll();
        $filteredEntities = [];

        foreach ($entities as $entity) {
            if ($this->checkCriteria($entity, $criteria)) {
                $filteredEntities[] = $entity;
            }
        }

        return $filteredEntities;
    }

    /**
     * @param array $criteria
     *
     * @return object|null
     */
    public function findOneBy(array $criteria): ?object
    {
        $entities = $this->findAll();

        foreach ($entities as $entity) {
            if ($this->checkCriteria($entity, $criteria)) {
                return $entity;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->entityClass;
    }

    /**
     * @return EntityManagerStub
     */
    public function getManager(): EntityManagerStub
    {
        if ($this->entityManager === null) {
            $this->entityManager = new EntityManagerStub();
            $this->entityManager->setRepository($this);
        }

        return $this->entityManager;
    }

    /**
     * @param object $entity
     * @param        $criteria
     *
     * @return boolean
     */
    public function checkCriteria(object $entity, $criteria): bool
    {
        foreach ($criteria as $key => $value) {
            $entityValue = $this->propertyAccessor->getValue($entity, $key);

            if ($entityValue !== $value) {
                return false;
            }
        }

        return true;
    }
}
