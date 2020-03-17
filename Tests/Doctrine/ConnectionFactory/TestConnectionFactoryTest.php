<?php

namespace RichCongress\Bundle\UnitBundle\Tests\Doctrine\ConnectionFactory;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\DBAL\DBALException;
use PHPUnit\Framework\TestCase;
use RichCongress\Bundle\UnitBundle\Doctrine\ConnectionFactory\TestConnectionFactory;

/**
 * Class TestConnectionFactoryTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\Doctrine\ConnectionFactory
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\Doctrine\ConnectionFactory\TestConnectionFactory
 */
class TestConnectionFactoryTest extends TestCase
{
    /**
     * @var string
     */
    protected static $initialTestToken;

    /**
     * @var TestConnectionFactory
     */
    protected $factory;

    /**
     * @var ConnectionFactory
     */
    protected $innerFactory;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$initialTestToken = getenv('TEST_TOKEN');
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        putenv('TEST_TOKEN=' . static::$initialTestToken);
    }

    public function setUp()
    {
        parent::setUp();

        $this->innerFactory = new ConnectionFactory([]);
        $this->factory = new TestConnectionFactory($this->innerFactory);
    }

    /**
     * @return void
     *
     * @throws DBALException
     */
    public function testCreateConnectionWithSingleProcess(): void
    {
        $params = [
            'driver' => 'pdo_sqlite',
            'dbname' => 'TestDatabase',
            'path'   => '/path/__DBNAME__.db',
        ];

        putenv('TEST_TOKEN=');
        $connection = $this->factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('TestDatabase', $connectionParams['dbname']);
        self::assertSame('/path/TestDatabase.db', $connectionParams['path']);
    }

    /**
     * @return void
     *
     * @throws DBALException
     */
    public function testCreateConnectionWithDefault(): void
    {
        $params = [
            'driver' => 'pdo_sqlite',
            'path'   => '/path/__DBNAME__.db',
        ];

        putenv('TEST_TOKEN=');
        $connection = $this->factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('dbTest', $connectionParams['dbname']);
        self::assertSame('/path/dbTest.db', $connectionParams['path']);
    }

    /**
     * @return void
     *
     * @throws DBALException
     */
    public function testCreateConnectionWithMultipleProcess(): void
    {
        $params = [
            'driver' => 'pdo_sqlite',
            'dbname' => 'TestDatabase',
            'path'   => '/path/__DBNAME__.db',
        ];

        putenv('TEST_TOKEN=1');
        $connection = $this->factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('TestDatabase_1', $connectionParams['dbname']);
        self::assertSame('/path/TestDatabase_1.db', $connectionParams['path']);
    }
}
