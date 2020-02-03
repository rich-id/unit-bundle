<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use RichCongress\Bundle\UnitBundle\TestCase\VoterTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\User;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Security\DummyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class VoterTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\VoterTestCase
 */
class VoterTestCaseTest extends VoterTestCase
{
    /**
     * @return void
     */
    public function testGetTokenAnonymous(): void
    {
        $token = $this->getToken();

        self::assertSame('anon.', $token->getUser());
    }

    /**
     * @return void
     */
    public function testGetTokenWithUser(): void
    {
        $user = new User();
        $token = $this->getToken($user, ['ROLE_ADMIN']);

        self::assertSame($user, $token->getUser());
        self::assertContains('ROLE_ADMIN', $token->getRoleNames());
    }

    /**
     * @return void
     */
    public function testVoteNoVoter(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The voter is not well initialized. Please check your setUp function.');

        $this->vote('', '');
    }

    /**
     * @return void
     */
    public function testVote(): void
    {
        $this->voter = new DummyVoter();
        $result = $this->vote('', '');

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }
}
