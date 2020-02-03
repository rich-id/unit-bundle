<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Stubs;

use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Stubs\ValidationContextStub;

/**
 * Class ValidationContextStubTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Stubs\ValidationContextStub
 */
class ValidationContextStubTest extends TestCase
{
    /**
     * @var ValidationContextStub
     */
    protected $context;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->context = new ValidationContextStub();
    }

    /**
     * @return void
     */
    public function testCountViolations(): void
    {
        self::assertSame(0, $this->context->countViolations());

        $this->context->addViolation('Violation');

        self::assertSame(1, $this->context->countViolations());
    }
}
