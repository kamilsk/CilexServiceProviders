<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\Doctrine;

use Cilex\Application;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\MigrationException;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper;
use OctoLab\Cilex\ServiceProvider\DoctrineServiceProvider;
use OctoLab\Cilex\TestCase;
use OctoLab\Common\Doctrine\Migration\FileBasedMigration;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CheckMigrationCommandTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function execute(Application $app)
    {
        $app->register($this->getConfigServiceProviderForDoctrine());
        $app->register(new DoctrineServiceProvider());
        $app->command($command = new CheckMigrationCommand('test'));
        $command->getHelperSet()->set(
            new ConfigurationHelper(null, $this->configuration->reveal()),
            'configuration'
        );
        $reflection = (new \ReflectionObject($command))->getMethod('execute');
        $reflection->setAccessible(true);

        $output = $this->getBufferedOutput();
        $migration = __DIR__ . '/migrations/2/ISSUE-29/upgrade.sql';
        $input = new ArgvInput(
            [
                $command->getName(),
                $migration,
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(
            sprintf(
                "Migration %s contains\n1. CREATE TABLE test ( id INT, title VARCHAR(8) NOT NULL, PRIMARY KEY (id) )",
                $migration
            ),
            $output->fetch()
        );

        $migration = __DIR__ . '/migrations/2/ISSUE-29/empty.sql';
        $input = new ArgvInput(
            [
                $command->getName(),
                $migration,
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(sprintf('Migration %s is empty', $migration), $output->fetch());

        $migration = '20160320120000';
        $input = new ArgvInput(
            [
                $command->getName(),
                $migration,
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        $needle = <<<EOF
Upgrade by migration %s contains
1. CREATE TABLE test ( id INT, title VARCHAR(8) NOT NULL, PRIMARY KEY (id) )
Downgrade by migration %s contains
1. DROP TABLE test CASCADE
EOF;
        self::assertContains(sprintf($needle, $migration, $migration), $output->fetch());

        $migration = Version20160320120000::class;
        $input = new ArgvInput(
            [
                $command->getName(),
                $migration,
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(
            sprintf($needle, $migration, $migration),
            $output->fetch()
        );

        $migration = '20160320000000';
        $input = new ArgvInput(
            [
                $command->getName(),
                $migration,
            ],
            $command->getDefinition()
        );
        try {
            $reflection->invoke($command, $input, $output);
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertContains(
                sprintf(
                    'Migration must be an instance of %s. Use "--dry-run" option of %s to see its\' content instead.',
                    FileBasedMigration::class,
                    MigrateCommand::class
                ),
                $e->getMessage()
            );
        }

        try {
            $input = new ArgvInput(
                [
                    $command->getName(),
                    'Unknown\\MigrationClass',
                ],
                $command->getDefinition()
            );
            $reflection->invoke($command, $input, $output);
            self::fail(sprintf('%s exception expected.', MigrationException::class));
        } catch (MigrationException $e) {
            self::assertContains('Could not find migration version Unknown\\MigrationClass', $e->getMessage());
        }
    }

    protected function setUp()
    {
        parent::setUp();
        $this->configuration = $this->prophesize(Configuration::class);
        $this->configuration->getVersion('20160320120000')->willReturn(new class {
            public function getMigration(): Version20160320120000
            {
                return (new \ReflectionClass(Version20160320120000::class))->newInstanceWithoutConstructor();
            }
        });
        $this->configuration->getVersion('20160320000000')->will(function (array $args) {
            $configuration = new Configuration(new Connection([
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'port' => 3306,
                'dbname' => 'database',
                'user' => 'user',
                'password' => 'pass',
            ], new Driver()));
            $configuration->setMigrationsDirectory(__DIR__);
            $configuration->setMigrationsNamespace(__NAMESPACE__);
            $configuration->setMigrationsTableName('migration');
            return $configuration->getVersion($args[0]);
        });
        $this->configuration->getMigrationsNamespace()->willReturn(__NAMESPACE__);
        $this->configuration->getVersion('Unknown\\MigrationClass')->will(function (array $args) {
            throw MigrationException::unknownMigrationVersion($args[0]);
        });
    }
}
