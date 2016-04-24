<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\Doctrine;

use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Schema\Schema;
use OctoLab\Common\Doctrine\Migration\FileBasedMigration;
use OctoLab\Common\Doctrine\Util\Parser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class CheckMigrationCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('check')
            ->setDescription('Return list of sql queries in file or migration.')
            ->addArgument('migration', InputArgument::REQUIRED, 'Path to sql file or migration class.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     * @throws \Doctrine\DBAL\Migrations\MigrationException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migration = $input->getArgument('migration');
        if (is_file($migration)) {
            return $this->checkFileMigration($migration, $output);
        }
        $class = $this->getClass($migration, $input, $output);
        $object = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
        if ($object instanceof FileBasedMigration) {
            return $this->checkFileBasedMigration($object, $migration, $output);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Migration must be an instance of %s. Use "--dry-run" option of %s to see its\' content instead.',
                FileBasedMigration::class,
                MigrateCommand::class
            ));
        }
    }

    /**
     * @param string $file
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    private function checkFileMigration(string $file, OutputInterface $output): int
    {
        $queries = Parser::extractSql(file_get_contents($file));
        if (!empty($queries)) {
            $output->writeln(sprintf('<comment>Migration %s contains</comment>', $file));
            $this->printQueries($queries, $output);
        } else {
            $output->writeln(sprintf('<comment>Migration %s is empty</comment>', $file));
        }
        return 0;
    }

    /**
     * @param FileBasedMigration $migration
     * @param string $migrationName
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    private function checkFileBasedMigration(
        FileBasedMigration $migration,
        string $migrationName,
        OutputInterface $output
    ): int {
        $methods = ['upgrade' => 'preUp', 'downgrade' => 'preDown'];
        // the right way is to use the same schema that is passed to a real migration
        $schema = new Schema();
        foreach ($methods as $direction => $method) {
            $migration->{$method}($schema);
            if ($migration->getQueries()) {
                $output->writeln(
                    sprintf('<comment>%s by migration %s</comment>', ucfirst($direction), $migrationName)
                );
                $this->printQueries($migration->getQueries(), $output);
            } else {
                $output->writeln(
                    sprintf('<comment>%s by migration %s is empty</comment>', ucfirst($direction), $migrationName)
                );
            }
        }
        return 0;
    }

    /**
     * @param string $migration
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Migrations\MigrationException
     * @throws \InvalidArgumentException
     */
    private function getClass(string $migration, InputInterface $input, OutputInterface $output): string
    {
        if (preg_match('/^\d{14}$/', $migration)) {
            $configuration = $this->getMigrationConfiguration($input, $output);
            $configuration->validate();
            // the right way is to use $configuration->getVersion(), but it is difficult for mocking
            $class = $configuration->getMigrationsNamespace() . '\Version' . $migration;
        } elseif (class_exists($migration)) {
            $class = $migration;
        } else {
            throw new \InvalidArgumentException('Migration must be a valid file or version or class.');
        }
        return $class;
    }

    /**
     * @param string[] $queries
     * @param OutputInterface $output
     *
     * @throws \InvalidArgumentException
     */
    private function printQueries(array $queries, OutputInterface $output)
    {
        $counter = strlen((string)count($queries));
        foreach ($queries as $i => $query) {
            $output->writeln(
                sprintf('<info>%s. %s</info>', str_pad((string)($i + 1), $counter, ' ', STR_PAD_LEFT), $query)
            );
        }
    }
}
