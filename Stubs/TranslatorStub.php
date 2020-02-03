<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Stubs;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TranslatorStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Stubs
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TranslatorStub implements TranslatorInterface
{
    /**
     * @var array
     */
    public $translations = [];

    /**
     * @param string      $id
     * @param array       $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string|void
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string
    {
        if (!array_key_exists($id, $this->translations)) {
            return '';
        }

        $translation = $this->translations[$id];

        foreach ($parameters as $key => $value) {
            $translation = str_replace($key, $value, $translation);
        }

        return $translation;
    }

    /**
     * @param string $id
     * @param string $translation
     *
     * @return self
     */
    public function addTranslation(string $id, string $translation): self
    {
        $this->translations[$id] = $translation;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }
}
