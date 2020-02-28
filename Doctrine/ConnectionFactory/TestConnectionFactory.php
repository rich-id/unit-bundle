<?php

namespace RichCongress\Bundle\UnitBundle\Doctrine\ConnectionFactory;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Class TestConnectionFactory
 *
 * @package   RichCongress\Bundle\UnitBundle\Doctrine\ConnectionFactory
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class TestConnectionFactory extends ConnectionFactory
{
    /**
     * Create a connection by name.
     *
     * @param array         $params
     * @param Configuration $config
     * @param EventManager  $eventManager
     * @param array         $mappingTypes
     *
     * @return Connection
     * @throws DBALException
     */
    public function createConnection(array $params, Configuration $config = null, EventManager $eventManager = null, array $mappingTypes = []): Connection
    {
        $parameters = self::processParameters($params);

        return parent::createConnection($parameters, $config, $eventManager, $mappingTypes);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function processParameters(array $params): array
    {
        $params['dbName'] = $params['dbName'] ?? 'dbTest';
        $params['dbName'] .= getenv('TEST_TOKEN');

        return $params;
    }
}
