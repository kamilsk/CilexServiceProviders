<?php

namespace OctoLab\Cilex\Tests\Doctrine;

use OctoLab\Cilex\Doctrine\FileBasedMigration;

/**
 * phpunit src/Tests/Doctrine/FileBasedMigrationTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class FileBasedMigrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function emptyMigration()
    {
        $migration = $this->getMigrationMock(EmptyMigration::class);
        self::assertEmpty($migration->getUpgradeMigrations());
        self::assertEmpty($migration->getDowngradeMigrations());
    }

    /**
     * @test
     */
    public function testMigration()
    {
        $migration = $this->getMigrationMock(TestMigration::class);
        self::assertCount(1, $migration->getUpgradeMigrations());
        self::assertCount(1, $migration->getDowngradeMigrations());
        self::assertFileExists($migration->getFullPath($migration->getUpgradeMigrations()[0]));
        self::assertFileExists($migration->getFullPath($migration->getDowngradeMigrations()[0]));
    }

    /**
     * @test
     */
    public function partialMigration()
    {
        $migration = $this->getMigrationMock(PartialMigration::class);
        self::assertEmpty($migration->getUpgradeMigrations());
        self::assertCount(1, $migration->getDowngradeMigrations());
        self::assertFileExists($migration->getFullPath($migration->getDowngradeMigrations()[0]));
    }

    /**
     * @param string $class
     *
     * @return FileBasedMigration
     */
    private function getMigrationMock($class)
    {
        /** @var FileBasedMigration $instance */
        $instance = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
        return $instance;
    }
}
