<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use RichCongress\Bundle\UnitBundle\Exception\FixturesNotEnabledException;
use RichCongress\Bundle\UnitBundle\TestConfiguration\TestContext;
use RichCongress\Bundle\UnitBundle\TestTrait\AuthenticationTrait;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use RichCongress\Bundle\UnitBundle\Utility\TestConfigurationExtractor;

/**
 * Class FixtureTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class FixturesTestCase extends WebTestCase
{
    /**
     * Import the authentication trait
     */
    use AuthenticationTrait;

    /**
     * @internal Use afterTest instead
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->executeAfterTest();

        if (TestContext::$needFixtures) {
            $this->authenticationTearDown();
        }

        parent::tearDown();
    }

    /**
     * Loads the reference with the appropriate EntityManager
     *
     * @param string $reference
     *
     * @return mixed
     */
    protected function getReference(string $reference)
    {
        FixturesNotEnabledException::checkAndThrow();

        $object = FixturesManager::getReference($reference);
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getManager();
        $meta = $entityManager->getClassMetadata(\get_class($object));
        $identity = FixturesManager::getIdentity($reference);

        if ($identity !== null && !$entityManager->contains($object)) {
            $object = $entityManager->getReference(
                $meta->getName(),
                $identity
            );

            FixturesManager::setReference($reference, $object);
        }

        return $object;
    }

}
