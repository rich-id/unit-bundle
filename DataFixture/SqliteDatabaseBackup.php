<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DataFixture;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Liip\TestFixturesBundle\Services\DatabaseBackup\DatabaseBackupInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SqliteDatabaseBackup
 *
 * @package   RichCongress\Bundle\UnitBundle\DataFixture
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class SqliteDatabaseBackup implements DatabaseBackupInterface
{
    /**
     * @var int
     */
    protected $cacheLifetime;

    /**
     * @var SqliteDatabaseBackup
     */
    protected $innerDatabaseBackup;

    /**
     * SqliteDatabaseBackup constructor.
     *
     * @param ParameterBagInterface $parameterBag
     * @param SqliteDatabaseBackup  $sqliteDatabaseBackup
     */
    public function __construct(ParameterBagInterface $parameterBag, SqliteDatabaseBackup $sqliteDatabaseBackup)
    {
        $this->cacheLifetime = $parameterBag->get('rich_congress_unit.db_cache.lifetime');
        $this->innerDatabaseBackup = $sqliteDatabaseBackup;
    }

    /**
     * @param array $metadatas
     * @param array $classNames
     * @param bool  $append
     *
     * @return void
     */
    public function init(array $metadatas, array $classNames, bool $append = false): void
    {
        $this->innerDatabaseBackup->init($metadatas, $classNames, $append);
    }

    /**
     * @return string
     */
    public function getBackupFilePath(): string
    {
        return $this->innerDatabaseBackup->getBackupFilePath();
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function isBackupActual(): bool
    {
        if (!$this->innerDatabaseBackup->isBackupActual()) {
            return false;
        }

        $backupLastModifiedDateTime = \DateTime::createFromFormat('U', filemtime($this->getBackupFilePath()));
        $minimumValidDate = new \DateTime('now - ' . $this->cacheLifetime . ' minutes');

        return $minimumValidDate < $backupLastModifiedDateTime;
    }

    /**
     * @param AbstractExecutor $executor
     *
     * @return void
     */
    public function backup(AbstractExecutor $executor): void
    {
        $this->innerDatabaseBackup->backup($executor);
    }

    /**
     * @param AbstractExecutor $executor
     * @param array            $excludedTables
     *
     * @return void
     */
    public function restore(AbstractExecutor $executor, array $excludedTables = []): void
    {
        $this->innerDatabaseBackup->restore($executor, $excludedTables);
    }
}
