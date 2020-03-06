<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture;

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
     * @return void
     */
    protected function loadFixtures(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $data = [
                'name'    => 'Name ' . $i,
                'keyname' => 'keyname_' . $i,
            ];

            $this->createObject('entity_' . $i, DummyEntity::class, $data);
        }

        $this->createFromDefault('entity_from_default', [
            'name' => 'From Default',
        ]);

        $this->createFrom('entity_1', 'entity_1_copy', [
            'name' => 'Copy',
        ]);
    }

    /**
     * @return DummyEntity
     */
    protected function generateDefaultEntity(): DummyEntity
    {
        $id = $this->count + 1;
        $data = [
            'name'    => 'Name ' . $id,
            'keyname' => 'keyname_' . $id,
        ];

        /** @var DummyEntity $dummyEntity */
        $dummyEntity = $this->buildObject(DummyEntity::class, $data);

        return $dummyEntity;
    }
}
