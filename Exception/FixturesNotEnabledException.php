<?php

namespace RichCongress\Bundle\UnitBundle\Exception;

use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;

/**
 * Class FixturesNotEnabledException
 *
 * @package RichCongress\Bundle\UnitBundle\Exception
 * @author  Nicolas Guilloux <novares.x@gmail.com>
 */
class FixturesNotEnabledException extends AbstractCheckAndThrowException
{
    /**
     * @var string
     */
    protected static $error = 'You did not mentionned that you want to load the fixtures. Add the annotation @WithFixtures into the class or test PHP Doc.';

    /**
     * @var string
     */
    protected static $documentation = 'https://github.com/richcongress/unit-bundle/blob/v2.0.0/Docs/Annotations.md#using-withfixtures';

    /**
     * @return bool
     */
    protected static function check(): bool
    {
        return TestContext::$needFixtures;
    }
}
