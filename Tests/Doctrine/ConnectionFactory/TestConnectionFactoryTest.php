<?php

namespace RichCongress\Bundle\UnitBundle\Tests\Doctrine\ConnectionFactory;

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

    /**
     * @return void
     *
     * @throws DBALException
     */
    public function testCreateConnectionWithSingleProcess(): void
    {
        $params = [
            'driver' => 'pdo_sqlite',
            'dbName' => 'TestDatabase',
        ];

        putenv('TEST_TOKEN=');
        $factory = new TestConnectionFactory([]);
        $connection = $factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('TestDatabase', $connectionParams['dbName']);
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
        ];

        putenv('TEST_TOKEN=');
        $factory = new TestConnectionFactory([]);
        $connection = $factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('dbTest', $connectionParams['dbName']);
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
            'dbName' => 'TestDatabase',
        ];


        putenv('TEST_TOKEN=1');
        $factory = new TestConnectionFactory([]);
        $connection = $factory->createConnection($params);
        $connectionParams = $connection->getParams();

        self::assertSame('TestDatabase1', $connectionParams['dbName']);
    }
}
