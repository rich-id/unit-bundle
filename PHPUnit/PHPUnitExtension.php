<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\PHPUnit;

use DAMA\DoctrineTestBundle\PHPUnit\PHPUnitExtension as DamaPHPUnitExtension;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;

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
}
