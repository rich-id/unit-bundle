<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use Doctrine\ORM\EntityRepository;
use RichCongress\Bundle\UnitBundle\TestCase\RepositoryTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\BadDummyRepositoryTestCase;

/**
 * Class RepositoryTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @WithContainer
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\RepositoryTestCase
 */
class RepositoryTestCaseTest extends RepositoryTestCase
{
    public const ENTITY = DummyEntity::class;

    /**
     * @return void
     */
    public function testSetUp(): void
    {
        self::assertInstanceOf(EntityRepository::class, $this->repository);
        self::assertSame(DummyEntity::class, $this->repository->getClassName());
    }

    /**
     * @return void
     */
    public function testBadSetUp(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No entity defined for ' . BadDummyRepositoryTestCase::class);

        $testCase = new BadDummyRepositoryTestCase();
        $testCase->setUp();
    }
}
