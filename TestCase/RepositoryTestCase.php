<?php /** @noinspection NullPointerExceptionInspection */
declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TestCase;

use Doctrine\ORM\EntityRepository;

/**
 * Class RepositoryTestCase
 *
 * @package   RichCongress\Bundle\UnitBundle\TestCase
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class RepositoryTestCase extends TestCase
{
    /**
     * /!\ Needs to be overriden
     * Entity linked to the repository
     */
    public const ENTITY = '';

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @internal Use beforeTest instead
     *
     * @return void
     */
    public function setUp(): void
    {
        // Trick to execute de beforeTest() after the initialization
        $this->beforeTestExecuted = true;
        parent::setUp();
        $this->beforeTestExecuted = false;

        if (static::ENTITY === '') {
            throw new \LogicException('No entity defined for ' . static::class);
        }

        $this->repository = $this->getManager()->getRepository(static::ENTITY);

        $this->executeBeforeTest();
    }
}
