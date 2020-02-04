<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Stub;

use Doctrine\Common\DataFixtures\ReferenceRepository;

/**
 * Class ReferenceRepositoryStub
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Resources\Stub
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class ReferenceRepositoryStub extends ReferenceRepository
{
    /**
     * @var array
     */
    protected $references = [];

    /**
     * DummyDatabaseTool constructor.
     */
    public function __construct()
    {
        // Empty, bypass the parent constructeur
    }

    /**
     * @param string $name
     * @param object $reference
     *
     * @return void
     */
    public function setReference($name, $reference): void
    {
        $this->references[$name] = $reference;
    }

    /**
     * @param string $name
     *
     * @return object
     */
    public function getReference($name): object
    {
        if (! $this->hasReference($name)) {
            throw new \OutOfBoundsException(sprintf('Reference to "%s" does not exist', $name));
        }

        return $this->references[$name];
    }

    /**
     * @return array
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasReference($name): bool
    {
        return \array_key_exists($name, $this->references);
    }

    /**
     * @return array
     */
    public function getIdentities(): array
    {
        $identities = [];

        foreach ($this->references as $key => $value) {
            $identities[$key] = 'identity_' . $key;
        }

        return $identities;
    }
}
