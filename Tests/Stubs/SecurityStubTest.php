<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Stubs;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Stubs\SecurityStub;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;

/**
 * Class SecurityStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Stubs\SecurityStub
 */
class SecurityStubTest extends TestCase
{
    /**
     * @var SecurityStub
     */
    protected $security;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->security = new SecurityStub();
    }

    /**
     * @return void
     */
    public function testSetUser(): void
    {
        $user = new User();
        $this->security->setUser($user, ['ROLE_MAGIC']);

        self::assertSame($user, $this->security->getUser());
        self::assertContains('ROLE_MAGIC', $this->security->getRoleNames());
    }

    /**
     * @return void
     */
    public function testSetEmptyUser(): void
    {
        $this->security->setUser(null, ['ROLE_MAGIC']);

        self::assertNull($this->security->getUser());
        self::assertContains('ROLE_MAGIC', $this->security->getRoleNames());
    }
}
