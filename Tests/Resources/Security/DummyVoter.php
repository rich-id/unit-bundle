<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class DummyVoter
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Security
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyVoter implements VoterInterface
{
    /**
     * @param TokenInterface $token
     * @param mixed          $subject
     * @param array          $attributes
     *
     * @return int|void
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        return self::ACCESS_GRANTED;
    }
}
