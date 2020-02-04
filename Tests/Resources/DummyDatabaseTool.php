<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Liip\TestFixturesBundle\Services\FixturesLoaderFactory;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadDummyEntityData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture\LoadUserData;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Stub\ReferenceRepositoryStub;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DummyDatabaseTool
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyDatabaseTool extends AbstractDatabaseTool
{
    /**
     * DummyDatabaseTool constructor.
     */
    public function __construct()
    {
        // Empty, bypass the parent constructeur
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'dummyType';
    }

    /**
     * @return string
     */
    public function getDriverName(): string
    {
        return 'default';
    }

    /**
     * @param string|null $omName
     *
     * @return void
     */
    public function setObjectManagerName(string $omName = null): void
    {
        $this->omName = $omName;
    }

    /**
     * @param array $classNames
     * @param bool  $append
     *
     * @return AbstractExecutor
     */
    public function loadFixtures(array $classNames = [], bool $append = false): AbstractExecutor
    {
        $repository = new ReferenceRepositoryStub();

        if (\in_array('Error', $classNames, true)) {
            throw new \LogicException('Fake error');
        }

        if (\in_array(LoadUserData::class, $classNames, true)) {
            $repository->setReference('user', new User());
        }

        if (\in_array(LoadDummyEntityData::class, $classNames, true)) {
            $repository->setReference('dummy_entity', new DummyEntity());
        }

        $executor = \Mockery::mock(AbstractExecutor::class);
        $executor->shouldReceive('getReferenceRepository')->andReturn($repository);

        return $executor;
    }
}
