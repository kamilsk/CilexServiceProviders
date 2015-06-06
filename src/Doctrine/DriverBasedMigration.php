<?php

namespace OctoLab\Cilex\Doctrine;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Driver based migrations.
 *
 * Strategy:
 * - migration determines a preparatory method, e.g. preMysqliUp, preIbmDb2Down, etc.
 * - the preparatory method fills the "queries" property
 * - "up" and "down" run these queries
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class DriverBasedMigration extends AbstractMigration
{
    /** @var array */
    protected $queries = [];

    /**
     * @param Schema $schema
     */
    final public function preUp(Schema $schema)
    {
        $this->prepare('Up', $schema);
    }

    /**
     * @param Schema $schema
     */
    final public function up(Schema $schema)
    {
        $this->routine();
    }

    /**
     * @param Schema $schema
     */
    final public function preDown(Schema $schema)
    {
        $this->prepare('Down', $schema);
    }

    /**
     * @param Schema $schema
     */
    final public function down(Schema $schema)
    {
        $this->routine();
    }

    /**
     * @param string $direction
     * @param Schema $schema
     */
    private function prepare($direction, Schema $schema)
    {
        // mysqli, drizzle_pdo_mysql, ibm_db2, etc.
        $driver = $this->connection->getDriver()->getName();
        $parts = explode(' ', ucwords(str_replace('_', ' ', $driver)));
        $method = 'pre' . implode('', $parts) . $direction;
        if (method_exists($this, $method)) {
            // instead of `$callback = [$this, $method]; $callback($schema);`
            $this->run([$this, $method], $schema);
        }
    }

    /**
     * @param callable $callback
     * @param Schema $schema
     */
    private function run(callable $callback, Schema $schema)
    {
        $callback($schema);
    }

    private function routine()
    {
        foreach ($this->queries as $sql) {
            $this->addSql($sql);
        }
    }
}
