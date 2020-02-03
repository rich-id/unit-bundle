<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Util\LegacyTranslatorProxy;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ValidationContextStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ValidationContextStub extends ExecutionContext
{
    /**
     * ValidationContextStub constructor.
     */
    public function __construct()
    {
        $validatorBuilder = new ValidatorBuilder();
        $validator = $validatorBuilder->getValidator();
        $translator = new TranslatorStub();

        parent::__construct($validator, '', $translator);
    }

    /**
     * @return integer
     */
    public function countViolations(): int
    {
        return $this->getViolations()->count();
    }
}
