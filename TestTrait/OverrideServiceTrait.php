<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestTrait;

/**
 * Trait OverrideServiceTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\TestTrait
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait OverrideServiceTrait
{
    /**
     * @return string|null
     */
    public function getOverridenServiceName(): ?string
    {
        return static::OVERRIDEN_SERVICE !== ''
            ? static::OVERRIDEN_SERVICE
            : null;
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        // Do nothing, override this function if needed.
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        // Do nothing, override this function if needed.
    }
}
