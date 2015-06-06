<?php

namespace OctoLab\Cilex\Doctrine;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use OctoLab\Cilex\Doctrine\Util\Parser;

/**
 * File based migrations.
 *
 * Pattern: [/path/to/sql/migrations/][<major version>/][<ticket>/(upgrade|downgrade).sql]
 * - getBasePath()
 * - getMajorVersion()
 * - item of upgrade|downgrade array
 * Strategy:
 * - major version extends FileBasedMigration
 * - minor version extends major version
 * <strong>IMPORTANT:</strong> upgrade and downgrade must be private.
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @method string[] getUpgradeMigrations()
 * @method string[] getDowngradeMigrations()
 */
abstract class FileBasedMigration extends AbstractMigration
{
    /** @var array */
    private $upgrade = [];
    /** @var array */
    private $downgrade = [];

    /**
     * @return string
     */
    abstract public function getBasePath();

    /**
     * @return string
     */
    abstract public function getMajorVersion();

    /**
     * @param Schema $schema
     */
    final public function up(Schema $schema)
    {
        $files = array_map([$this, 'getFullPath'], $this->getUpgradeMigrations());
        $this->routine($files);
    }

    /**
     * @param Schema $schema
     */
    final public function down(Schema $schema)
    {
        $files = array_map([$this, 'getFullPath'], $this->getDowngradeMigrations());
        $this->routine($files);
    }

    /**
     * @param string $file
     *
     * @return string
     *
     * @api
     */
    public function getFullPath($file)
    {
        $path = $this->getBasePath();
        if ($this->getMajorVersion()) {
            $path .= '/';
            $path .= $this->getMajorVersion();
        }
        $path .= '/';
        $path .= $file;
        return $path;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     *
     * @api
     */
    public function __call($method, array $arguments)
    {
        if (!strcasecmp($method, 'getUpgradeMigrations')) {
            try {
                return $this->resolveHiddenProperty('upgrade');
            } catch (\ReflectionException $e) {
                return $this->upgrade;
            }
        } elseif (!strcasecmp($method, 'getDowngradeMigrations')) {
            try {
                return $this->resolveHiddenProperty('downgrade');
            } catch (\ReflectionException $e) {
                return $this->downgrade;
            }
        } else {
            throw new \BadMethodCallException(
                sprintf('Method %s::%s doesn\'t exists.', get_called_class($this), $method)
            );
        }
    }

    /**
     * @param array $files
     */
    private function routine(array $files)
    {
        $parser = new Parser();
        foreach ($files as $file) {
            $queries = $parser->extractSql(file_get_contents($file));
            foreach ($queries as $sql) {
                $this->addSql($sql);
            }
        }
    }

    /**
     * @param string $property
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    private function resolveHiddenProperty($property)
    {
        $reflection = (new \ReflectionObject($this))->getProperty($property);
        $reflection->setAccessible(true);
        return $reflection->getValue($this);
    }
}
