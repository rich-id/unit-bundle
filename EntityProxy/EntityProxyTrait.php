<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\EntityProxy;

/**
 * Trait EntityProxyTrait
 *
 * @package   RichCongress\Bundle\UnitBundle\EntityProxy
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
trait EntityProxyTrait
{
    /**
     * @param array $data
     *
     * @return self
     */
    public static function buildObject(array $data): self
    {
        $object = static::makeDefault();

        return $object->setValues($data);
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setValues(array $data): self
    {
        foreach ($data as $property => $value) {
            $this->setValue($property, $value);
        }

        return $this;
    }

    /**
     * @param string $property
     * @param mixed  $value
     *
     * @return mixed
     */
    public function setValue(string $property, $value): self
    {
        $this->$property = $value;

        return $this;
    }
}
