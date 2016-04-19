<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\Doctrine;

use OctoLab\Common\Doctrine\Migration\FileBasedMigration;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Version20160320120000 extends FileBasedMigration
{
    /**
     * {@inheritdoc}
     */
    public function getBasePath()
    {
        return __DIR__ . '/migrations';
    }

    /**
     * {@inheritdoc}
     */
    public function getMajorVersion()
    {
        return '2';
    }

    /**
     * {@inheritdoc}
     */
    public function getUpgradeMigrations()
    {
        return ['ISSUE-29/upgrade.sql'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDowngradeMigrations()
    {
        return ['ISSUE-29/downgrade.sql'];
    }
}
