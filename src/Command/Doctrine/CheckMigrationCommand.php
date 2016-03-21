<?php

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
     * @quality:method [C]
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     * @throws \Doctrine\DBAL\Migrations\MigrationException
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migration = $input->getArgument('migration');
        if (is_file($migration)) {
            $queries = Parser::extractSql(file_get_contents($migration));
            if (!empty($queries)) {
                $output->writeln(sprintf('<comment>Migration %s contains</comment>', $migration));
                $this->printQueries($queries, $output);
            } else {
                $output->writeln(sprintf('<comment>Migration %s is empty</comment>', $migration));
            }
            return 0;
        } elseif (preg_match('/^\d{14}$/', $migration)) {
            $configuration = $this->getMigrationConfiguration($input, $output);
            $configuration->validate();
            // the right way is to use $configuration->getVersion(), but it is difficult for mocking
            $class = $configuration->getMigrationsNamespace() . '\Version' . $migration;
        } elseif (class_exists($migration)) {
            $class = $migration;
        } else {
            throw new \InvalidArgumentException('Migration must be a valid file or version or class.');
        }
        // the right way is to use the same schema that is passed to a real migration
        $schema = new Schema();
        $object = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
        if ($object instanceof FileBasedMigration) {
            $methods = ['upgrade' => 'preUp', 'downgrade' => 'preDown'];
            foreach ($methods as $direction => $method) {
                $object->{$method}($schema);
                if ($object->getQueries()) {
                    $output->writeln(
                        sprintf('<comment>%s by migration %s</comment>', ucfirst($direction), $migration)
                    );
                    $this->printQueries($object->getQueries(), $output);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(
                'Migration must be an instance of %s. Use "--dry-run" option of %s to see its\' content instead.',
                FileBasedMigration::class,
                MigrateCommand::class
            ));
        }
        return 0;
    }

    /**
     * @param string[] $queries
     * @param OutputInterface $output
     *
     * @throws \InvalidArgumentException
     */
    private function printQueries(array $queries, OutputInterface $output)
    {
        $count = count($queries);
        foreach ($queries as $i => $query) {
            $output->writeln(
                sprintf('<info>%s. %s</info>', str_pad($i + 1, strlen($count), ' ', STR_PAD_LEFT), $query)
            );
        }
    }
}
