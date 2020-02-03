<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture;

use Doctrine\Persistence\ObjectManager;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture;

/**
 * Class LoadDummyEntityData
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class LoadDummyEntityData extends AbstractFixture
{
    /**
     * @inheritDoc
     */
    protected function loadFixtures(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $data = [
                'name'    => 'Name ' . $i,
                'keyname' => 'keyname_' . $i,
            ];

            $dummyEntity = $this->createObject('entity_' . $i, DummyEntity::class, $data);
        }
    }
}
