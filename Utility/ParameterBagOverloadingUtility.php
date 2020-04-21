<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Utility;

use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ParameterBagOverloadingUtility
 *
 * @package   RichCongress\Bundle\UnitBundle\Utility
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ParameterBagOverloadingUtility
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * ParameterBagOverloadingUtility constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Todo: Ugly.
     *
     * @return void
     */
    public function __invoke()
    {
        $newParameters = TestContext::$overloadParameters;

        if (!$this->parameterBag instanceof FrozenParameterBag) {
            $this->parameterBag->add($newParameters);
            return;
        }

        $reflectionClass = new \ReflectionClass($this->parameterBag);
        $parametersProperty = $reflectionClass->getProperty('parameters');
        $parameters = $parametersProperty->getValue($this->parameterBag);

        foreach ($newParameters as $key => $value) {
            $parameters[$key] = $value;
        }

        $parametersProperty->setValue($this->parameterBag, $parameters);
    }
}
