<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use RichCongress\Bundle\UnitBundle\OverrideService\OverrideServiceInterface;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;
use RichCongress\Bundle\UnitBundle\TestTrait\OverrideServiceTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Class ParameterBagStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ParameterBagStub extends ParameterBag implements OverrideServiceInterface
{
    protected static $overridenServices = 'logger';
    use OverrideServiceTrait;

    /**
     * @var array
     */
    public $parameters = [];

    /**
     * @return void
     */
    public function overrideParameters(): void
    {
        foreach (TestContext::$paramConverterOverloads as $property => $value) {
            $this->set($property, $value);
        }
    }
}
