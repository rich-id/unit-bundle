<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use RichCongress\Bundle\UnitBundle\Stubs\ValidationContextStub;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ConstraintTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ConstraintTestCase extends TestCase
{
    /**
     * @var ConstraintValidatorInterface
     */
    protected $validator;

    /**
     * @var Constraint|ConstraintValidatorInterface
     */
    protected $constraint;

    /**
     * @var ValidationContextStub
     */
    protected $context;

    /**
     * @internal Use beforeTest instead
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->context = new ValidationContextStub();

        if ($this->constraint instanceof ConstraintValidatorInterface) {
            $this->validator = $this->constraint;
        }

        if ($this->validator instanceof Constraint) {
            $this->constraint = $this->validator;
        }

        if ($this->validator instanceof  ConstraintValidatorInterface) {
            $this->validator->initialize($this->context);
        }

        if ($this->constraint instanceof Constraint) {
            $this->context->setConstraint($this->constraint);
        }
    }

    /**
     * @param mixed $value
     *
     * @return ConstraintViolationListInterface
     */
    public function validate($value): ConstraintViolationListInterface
    {
        $this->validator->validate($value, $this->constraint);

        return $this->context->getViolations();
    }
}
