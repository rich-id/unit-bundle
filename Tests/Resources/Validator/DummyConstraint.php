<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class DummyConstraint
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Validator
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyConstraint extends Constraint
{
    /**
     * @var boolean
     */
    public $makeItFail = false;
}
