<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Utility;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Exception\MethodNotFoundException;
use RichCongress\Bundle\UnitBundle\TestCase\ControllerTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestConfigurationExtractor;
use RichCongress\Bundle\UnitBundle\Tests\Command\DebugFixturesCommandTest;
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
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsContainer(WebTestCaseTest::class, 'testGetContainerWithoutAnnotation'));
        self::assertFalse(TestConfigurationExtractor::doesTestNeedsFixtures(WebTestCaseTest::class, 'testGetContainerWithoutAnnotation'));
    }

    /**
     * @return void
     */
    public function testClassWithFixtures(): void
    {
        TestConfigurationExtractor::register(DebugFixturesCommandTest::class);

        self::assertTrue(TestConfigurationExtractor::doesClassNeedsContainer(DebugFixturesCommandTest::class));
        self::assertTrue(TestConfigurationExtractor::doesClassNeedsFixtures(DebugFixturesCommandTest::class));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(DebugFixturesCommandTest::class, 'testExecute'));
        self::assertTrue(TestConfigurationExtractor::doesTestNeedsFixtures(DebugFixturesCommandTest::class, 'testExecute'));
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
        $this->expectException(MethodNotFoundException::class);
        $this->expectExceptionMessage('The method "unknownMethod" does not exist within the class "RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal\FixturesTestCaseTest"');

        self::assertTrue(TestConfigurationExtractor::doesTestNeedsContainer(FixturesTestCaseTest::class, 'unknownMethod'));
    }
}
