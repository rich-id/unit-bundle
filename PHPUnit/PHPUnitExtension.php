<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\PHPUnit;

use DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension as DamaPHPUnitExtension;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;

/**
 * Class PHPUnitExtension
 *
 * @package   RichCongress\Bundle\UnitBundle\PHPUnit
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class PHPUnitExtension extends DamaPHPUnitExtension
{
    /**
     * @return void
     *
     * @throws \Throwable
     */
    public function executeBeforeFirstTest(): void
    {
        parent::executeBeforeFirstTest();

        FixturesManager::loadFixtures();
        StaticDriver::commit();
    }

    /**
     * @param string $test
     *
     * @return void
     */
    public function executeBeforeTest(string $test): void
    {
        parent::executeBeforeTest($test);

        TestContext::parseTest($test);
        OverrideServicesUtility::executeSetUps();
    }

    /**
     * @param string $test
     * @param float  $time
     *
     * @return void
     */
    public function executeAfterTest(string $test, float $time): void
    {
        parent::executeAfterTest($test, $time);

        OverrideServicesUtility::executeTearDowns();
    }
}
