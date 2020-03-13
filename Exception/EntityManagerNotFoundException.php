<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityManagerNotFoundException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class EntityManagerNotFoundException extends AbstractCheckAndThrowException
{
    protected static $error = 'The Entity manager cannot be found. Check your Doctrine documentation.';

    /**
     * @param $entityManager
     *
     * @return bool
     */
    protected static function check($entityManager): bool
    {
        return $entityManager instanceof EntityManagerInterface;
    }
}
