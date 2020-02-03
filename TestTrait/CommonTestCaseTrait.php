<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

/**
 * Trait CommonTestCaseTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait CommonTestCaseTrait
{
    /**
     * @var boolean
     */
    protected $beforeTestExecuted = false;

    /**
     * @var boolean
     */
    protected $afterTestExecuted = false;

    /**
     * @return void
     */
    protected function beforeTest(): void
    {
        // Override it (avoid overriding setUp function)
    }

    /**
     * @return void
     */
    protected function afterTest(): void
    {
        // Override it (avoid overriding tearDown function)
    }

    /**
     * @internal
     *
     * @return void
     */
    protected function executeBeforeTest(): void
    {
        if (!$this->beforeTestExecuted) {
            $this->beforeTest();
            $this->beforeTestExecuted = true;
        }
    }

    /**
     * @internal
     *
     * @return void
     */
    protected function executeAfterTest(): void
    {
        if (!$this->afterTestExecuted) {
            $this->afterTest();
            $this->afterTestExecuted = true;
        }
    }
}
