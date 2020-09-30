<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;

/**
 * Class ContainerNotEnabledException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class ContainerNotEnabledException extends AbstractCheckAndThrowException
{
    /**
     * @var string
     */
    protected static $error = 'You did not mentionned that you want to load a container. Add the annotation @WithContainer into the class or test PHP Doc.';

    /**
     * @var string
     */
    protected static $documentation = 'https://github.com/richcongress/unit-bundle/blob/v2.0.0/Docs/Annotations.md#using-withcontainer';

    /**
     * @return bool
     */
    protected static function check(): bool
    {
        return TestContext::$needContainer === true;
    }
}
