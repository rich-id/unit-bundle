<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class DummyConstraintValidator
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Validator
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @var ExecutionContextInterface
     */
    public $context;

    /**
     * @param ExecutionContextInterface $context
     *
     * @return void
     */
    public function initialize(ExecutionContextInterface $context): void
    {
        $this->context = $context;
    }

    /**
     * @param mixed                      $value
     * @param Constraint|DummyConstraint $constraint
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($constraint->makeItFail) {
            $this->context->addViolation('No');
        }
    }
}
