<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

/**
 * Class BadTestRolesException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class BadTestRolesException extends \LogicException
{
    /**
     * @param array|null $userRoles
     * @param array      $expectations
     *
     * @return void
     */
    public static function checkAndThrow(?array $userRoles, array $expectations): void
    {
        $rolesCount = count($userRoles);

        if ($rolesCount === 0) {
            throw new self('You must define test_roles in the bundle configuration to use this function.');
        }

        $countExpectations = count($expectations);
        $rolesNames = array_keys($userRoles);

        if ($countExpectations !== $rolesCount) {
            throw new self(
                sprintf(
                    'The dataProvider has %d roles where the configuration waits for %d roles.',
                    $countExpectations,
                    $rolesCount
                )
            );
        }

        if ($rolesNames !== array_keys($expectations)) {
            throw new self('The roles in the given expectations don\'t match the existing roles.');
        }
    }
}
