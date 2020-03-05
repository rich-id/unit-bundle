<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\OverrideService;

use RichCongress\Bundle\UnitBundle\OverrideService\AbstractOverrideService;

/**
 * Class DummyOverrideService
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\OverrideService
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class DummyOverrideService extends AbstractOverrideService
{
    public static $overridenServices = 'test.service';

    /**
     * @var boolean
     */
    public $setUpExecuted = false;

    /**
     * @var boolean
     */
    public $tearDownExecuted = false;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->setUpExecuted = true;

        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->tearDownExecuted = true;

        parent::tearDown();
    }
}
