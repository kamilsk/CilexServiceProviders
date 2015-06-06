<?php

namespace OctoLab\Cilex\Tests\Doctrine;

use OctoLab\Cilex\Doctrine\FileBasedMigration;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class EmptyMigration extends FileBasedMigration
{
    /**
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__) . '/app/migrations';
    }

    /**
     * @return string
     */
    public function getMajorVersion()
    {
        return '7';
    }
}
