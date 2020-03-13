<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CsrfTokenManagerMissingException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class CsrfTokenManagerMissingException extends AbstractCheckAndThrowException
{
    /**
     * @var string
     */
    protected static $error = 'The Security\'s CSRF Token Manager is missing from the container.';

    /**
     * @var string
     */
    protected static $documentation = 'https://symfony.com/doc/current/security/csrf.html';

    /**
     * @param ContainerInterface $container
     *
     * @return bool
     */
    protected static function check(ContainerInterface $container): bool
    {
        return $container->has('security.csrf.token_manager');
    }
}
