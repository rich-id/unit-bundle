<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\OverrideService;

/**
 * Interface OverrideServiceInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
interface OverrideServiceInterface
{
    /**
     * @return string|null
     */
    public function getOverridenServiceName(): ?string;

    /**
     * Executed before each test
     *
     * @return void
     */
    public function setUp(): void;

    /**
     * Executed at the end of each test, when the service is destroyed
     *
     * @return void
     */
    public function tearDown(): void;
}
