<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Tests\Mock\KernelTestCaseMockTest;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Proxy\TestConfigurationExtractorProxy;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\BadDummyRepositoryTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyContainerTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyFixturesTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyTestWithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal\FixturesTestCaseTest;
use RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal\WebTestCaseTest;

/**
 * Class TestDependenciesUtilityTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Utility\TestConfigurationExtractor
 */
class TestConfigurationExtractorTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        TestConfigurationExtractorProxy::$annotationReader = null;
    }

    /**
     * @return void
     */
    public function testClassWithContainer(): void
    {
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsContainer(DummyContainerTestCase::class));
        self::assertFalse(TestConfigurationExtractorProxy::doesClassNeedsFixtures(DummyContainerTestCase::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsContainer(DummyContainerTestCase::class, 'testSetUp'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsFixtures(DummyContainerTestCase::class, 'testSetUp'));
    }

    /**
     * @return void
     */
    public function testClassWithTestWithContainer(): void
    {
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsContainer(WebTestCaseTest::class));
        self::assertFalse(TestConfigurationExtractorProxy::doesClassNeedsFixtures(WebTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsContainer(WebTestCaseTest::class, 'testGetEntityManager'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsFixtures(WebTestCaseTest::class, 'testGetEntityManager'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsContainer(WebTestCaseTest::class, 'testDoesClassAndTestNeedsContainerWithNoContainer'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsFixtures(WebTestCaseTest::class, 'testDoesClassAndTestNeedsContainerWithNoContainer'));
    }

    /**
     * @return void
     */
    public function testClassWithFixtures(): void
    {
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsContainer(DummyFixturesTestCase::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsFixtures(DummyFixturesTestCase::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsContainer(DummyFixturesTestCase::class, 'testExecute'));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsFixtures(DummyFixturesTestCase::class, 'testExcute'));
    }

    /**
     * @return void
     */
    public function testClassWithTestWithFixtures(): void
    {
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsContainer(FixturesTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesClassNeedsFixtures(FixturesTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsContainer(DummyTestWithFixtures::class, 'dummyFunction'));
        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsFixtures(DummyTestWithFixtures::class, 'dummyFunction'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsContainer(FixturesTestCaseTest::class, 'testCheckFixturesEnabledWithoutFixtures'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsFixtures(FixturesTestCaseTest::class, 'testCheckFixturesEnabledWithoutFixtures'));
    }

    /**
     * @return void
     */
    public function testClassWithNoContainer(): void
    {
        self::assertFalse(TestConfigurationExtractorProxy::doesClassNeedsContainer(KernelTestCaseMockTest::class));
        self::assertFalse(TestConfigurationExtractorProxy::doesClassNeedsFixtures(KernelTestCaseMockTest::class));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsContainer(KernelTestCaseMockTest::class, 'testDummyFunctions'));
        self::assertFalse(TestConfigurationExtractorProxy::doesTestNeedsFixtures(KernelTestCaseMockTest::class, 'testDummyFunctions'));
    }

    /**
     * @return void
     */
    public function testNotParsedTest(): void
    {
        self::assertFalse(TestConfigurationExtractorProxy::doesClassNeedsContainer(BadDummyRepositoryTestCase::class));
    }

    /**
     * @return void
     */
    public function testGetTestConfigurationForNonExistentMethod(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The method "unknownMethod" has not been parsed. Something went wrong.');

        self::assertTrue(TestConfigurationExtractorProxy::doesTestNeedsContainer(FixturesTestCaseTest::class, 'unknownMethod'));
    }
}
