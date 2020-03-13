<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfigurationExtractor;
use RichCongress\Bundle\UnitBundle\Tests\Mock\KernelTestCaseMockTest;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\BadDummyRepositoryTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyContainerTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyFixturesTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\TestCase\DummyTestWithFixtures;
use RichCongress\Bundle\UnitBundle\Tests\TestCase\ControllerTestCaseTest;
use RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal\FixturesTestCaseTest;
use RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal\WebTestCaseTest;

/**
 * Class TestDependenciesUtilityTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfigurationExtractor
 */
class TestConfigurationExtractorTest extends TestCase
{
    /**
     * @return void
     */
    public function testClassWithContainer(): void
    {
        TestConfigurationExtractor::register(ControllerTestCaseTest::class);

        self::assertTrue(TestConfigurationExtractor::doesClassNeedsContainer(ControllerTestCaseTest::class));
        self::assertFalse(TestConfigurationExtractor::doesClassNeedsFixtures(ControllerTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(ControllerTestCaseTest::class, 'testGetCsrfTokenWithContainerHasIntention'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(ControllerTestCaseTest::class, 'testGetCsrfTokenWithContainerHasIntention'));
    }

    /**
     * @return void
     */
    public function testClassWithTestWithContainer(): void
    {
        TestConfigurationExtractor::register(WebTestCaseTest::class);

        self::assertTrue(TestConfigurationExtractor::doesClassNeedsContainer(WebTestCaseTest::class));
        self::assertFalse(TestConfigurationExtractor::doesClassNeedsFixtures(WebTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(WebTestCaseTest::class, 'testGetEntityManager'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(WebTestCaseTest::class, 'testGetEntityManager'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsContainer(WebTestCaseTest::class, 'testDoesClassAndTestNeedsContainerWithNoContainer'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(WebTestCaseTest::class, 'testDoesClassAndTestNeedsContainerWithNoContainer'));
    }

    /**
     * @return void
     */
    public function testClassWithFixtures(): void
    {
        TestConfigurationExtractor::register(DummyFixturesTestCase::class);

        self::assertTrue(TestConfigurationExtractor::doesClassNeedsContainer(DummyFixturesTestCase::class));
        self::assertTrue(TestConfigurationExtractor::doesClassNeedsFixtures(DummyFixturesTestCase::class));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(DummyFixturesTestCase::class, 'testExecute'));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsFixtures(DummyFixturesTestCase::class, 'testExcute'));
    }

    /**
     * @return void
     */
    public function testClassWithTestWithFixtures(): void
    {
        TestConfigurationExtractor::register(FixturesTestCaseTest::class);
        TestConfigurationExtractor::register(DummyFixturesTestCase::class);

        self::assertTrue(TestConfigurationExtractor::doesClassNeedsContainer(FixturesTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractor::doesClassNeedsFixtures(FixturesTestCaseTest::class));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(DummyTestWithFixtures::class, 'dummyFunction'));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsFixtures(DummyTestWithFixtures::class, 'dummyFunction'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsContainer(FixturesTestCaseTest::class, 'testCheckFixturesEnabledWithoutFixtures'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(FixturesTestCaseTest::class, 'testCheckFixturesEnabledWithoutFixtures'));
    }

    /**
     * @return void
     */
    public function testClassWithNoContainer(): void
    {
        TestConfigurationExtractor::register(KernelTestCaseMockTest::class);

        self::assertFalse(TestConfigurationExtractor::doesClassNeedsContainer(KernelTestCaseMockTest::class));
        self::assertFalse(TestConfigurationExtractor::doesClassNeedsFixtures(KernelTestCaseMockTest::class));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsContainer(KernelTestCaseMockTest::class, 'testDummyFunctions'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(KernelTestCaseMockTest::class, 'testDummyFunctions'));
    }

    /**
     * @return void
     */
    public function testNotParsedTest(): void
    {
        self::assertFalse(TestConfigurationExtractor::doesClassNeedsContainer(BadDummyRepositoryTestCase::class));
    }

    /**
     * @return void
     */
    public function testGetTestConfigurationForNonExistentMethod(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The method "unknownMethod" has not been parsed. Something went wrong.');

        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(FixturesTestCaseTest::class, 'unknownMethod'));
    }
}
