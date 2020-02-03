<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture;

use Doctrine\Persistence\ObjectManager;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\DataFixture\AbstractFixture;

/**
 * Class LoadUserData
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\DataFixture
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class LoadUserData extends AbstractFixture
{
    /**
     * @inheritDoc
     */
    protected function loadFixtures(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $data = [
                'username' => 'username_' . $i,
                'password' => 'password_' . $i,
            ];

            $user = $this->createObject('user_' . $i, User::class, $data);
        }
    }
}
