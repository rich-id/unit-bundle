<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Mock;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use RichCongress\Bundle\UnitBundle\Resources\Stub\KernelTestCaseStub;

/**
 * Class KernelTestCaseMockTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Mock
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Resources\Stub\KernelTestCaseStub
 */
class KernelTestCaseMockTest extends MockeryTestCase
{
    /**
     * @return void
     */
    public function testDummyFunctions(): void
    {
        $kernelTestCase = new KernelTestCaseStub();
        $manager = \Mockery::mock(ObjectManager::class);
        $repository = \Mockery::mock(ProxyReferenceRepository::class);
        $executor = \Mockery::mock(AbstractExecutor::class);

        $kernelTestCase->postFixtureBackupRestore('');
        $kernelTestCase->preFixtureBackupRestore($manager, $repository, '');
        $kernelTestCase->postReferenceSave($manager, $executor, '');
        $kernelTestCase->preReferenceSave($manager, $executor, null);
        $kernelTestCase->postFixtureSetup();

        $executor->shouldNotReceive('anything');
    }
}
