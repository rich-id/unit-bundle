<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Class DuplicatedContainersException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class DuplicatedContainersException extends AbstractCheckAndThrowException
{
    protected static $error = 'Multiple containers were used during the test. This may be caused by a service used before the client creation or because two clients were created during the test.';

    /**
     * @param bool               $containerGetBeforeClient
     * @param KernelBrowser|null $client
     *
     * @return bool
     */
    protected static function check(bool $containerGetBeforeClient, ?KernelBrowser $client): bool
    {
        return $containerGetBeforeClient === false && $client === null;
    }
}
