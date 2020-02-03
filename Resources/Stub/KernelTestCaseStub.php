<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Resources\Stub;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class KernelTestCaseMock
 *
 * @package   RichCongress\Bundle\UnitBundle\Resources\Stub
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class KernelTestCaseStub extends KernelTestCase
{
    /**
     * Callback function to be executed after Schema creation.
     * Use this to execute acl:init or other things necessary.
     */
    public function postFixtureSetup(): void
    {
    }

    /**
     * Callback function to be executed after Schema restore.
     *
     * @param string $backupFilePath Path of file used to backup the references of the data fixtures
     */
    public function postFixtureBackupRestore(string $backupFilePath): void
    {
    }

    /**
     * Callback function to be executed before Schema restore.
     *
     * @param ObjectManager            $manager
     * @param ProxyReferenceRepository $referenceRepository
     * @param string                   $backupFilePath
     */
    public function preFixtureBackupRestore(
        ObjectManager $manager,
        ProxyReferenceRepository $referenceRepository,
        string $backupFilePath
    ): void {
    }

    /**
     * Callback function to be executed after save of references.
     *
     * @param ObjectManager    $manager
     * @param AbstractExecutor $executor
     * @param string           $backupFilePath
     */
    public function postReferenceSave(ObjectManager $manager, AbstractExecutor $executor, string $backupFilePath): void
    {
    }

    /**
     * Callback function to be executed before save of references.
     *
     * @param ObjectManager    $manager
     * @param AbstractExecutor $executor
     * @param string|null      $backupFilePath
     */
    public function preReferenceSave(ObjectManager $manager, AbstractExecutor $executor, ?string $backupFilePath): void
    {
    }
}
