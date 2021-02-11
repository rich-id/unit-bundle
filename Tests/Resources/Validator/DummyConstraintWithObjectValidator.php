<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class DummyConstraintWithObjectValidator
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Validator
 * @author    Matthias Devlamynck <mdevlamynck@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyConstraintWithObjectValidator implements ConstraintValidatorInterface
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
        if ($this->context->getObject()->makeItFail) {
            $this->context->addViolation('No');
        }
    }
}
