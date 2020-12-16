<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase;

use RichCongress\Bundle\UnitBundle\Stubs\ValidationContextStub;
use RichCongress\Bundle\UnitBundle\TestCase\ConstraintTestCase;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Validator\DummyConstraint;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Validator\DummyConstraintValidator;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Validator\DummyConstraintWithObject;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Validator\DummyConstraintWithObjectValidator;

/**
 * Class ConstraintTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestCase\ConstraintTestCase
 */
class ConstraintTestCaseTest extends ConstraintTestCase
{
    /**
     * @return void
     */
    public function testSetUpWithConstraint(): void
    {
        $this->constraint = new DummyConstraintValidator();
        $this->setUp();

        self::assertSame($this->constraint, $this->validator);
    }

    /**
     * @return void
     */
    public function testSetUpWithValidator(): void
    {
        $this->validator = new DummyConstraint();
        $this->setUp();

        self::assertSame($this->constraint, $this->validator);
    }

    /**
     * @return void
     */
    public function testSetUpWithConstraintAndValidator(): void
    {
        $this->constraint = new DummyConstraint();
        $this->validator = new DummyConstraintValidator();
        $this->setUp();

        self::assertInstanceOf(ValidationContextStub::class, $this->context);
        self::assertSame($this->context, $this->validator->context);
        self::assertSame($this->constraint, $this->context->getConstraint());
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {
        $this->constraint = new DummyConstraint();
        $this->validator = new DummyConstraintValidator();
        $this->setUp();

        self::assertEmpty($this->validate(''));

        $this->constraint->makeItFail = true;

        self::assertCount(1, $this->validate(''));
    }

    /**
     * @return void
     */
    public function testValidatePropertyWithObjectInContext(): void
    {
        $this->constraint = new DummyConstraintWithObject();
        $this->validator = new DummyConstraintWithObjectValidator();
        $this->setUp();

        self::assertEmpty($this->validate('', (object) ['makeItFail' => false]));

        self::assertCount(1, $this->validate('', (object) ['makeItFail' => true]));
    }
}
