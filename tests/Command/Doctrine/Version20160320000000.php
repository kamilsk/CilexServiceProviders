<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\Doctrine;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Version20160320000000 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema)
    {
    }
}
