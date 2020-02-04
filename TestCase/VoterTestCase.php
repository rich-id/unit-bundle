<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */
declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class VoterTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class VoterTestCase extends TestCase
{
    /**
     * @var VoterInterface
     */
    protected $voter;

    /**
     * @param UserInterface|null $user
     * @param array              $roles
     *
     * @return UsernamePasswordToken
     */
    public function getToken(UserInterface $user = null, array $roles = []): UsernamePasswordToken
    {
        return new UsernamePasswordToken(
            $user ?? 'anon.',
            null,
            'main',
            $roles
        );
    }

    /**
     * @param mixed              $subject
     * @param string|array       $attributes
     * @param UserInterface|null $user
     *
     * @return int
     */
    public function vote($subject, $attributes, UserInterface $user = null): int
    {
        if (!$this->voter instanceof VoterInterface) {
            throw new \LogicException('The voter is not well initialized. Please check your setUp function.');
        }

        $attributes = (array) $attributes;
        $token = $this->getToken($user);

        return $this->voter->vote($token, $subject, $attributes);
    }
}
