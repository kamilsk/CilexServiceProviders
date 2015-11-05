<?php

namespace OctoLab\Cilex\Doctrine\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use OctoLab\Cilex\Doctrine\FileBasedMigration;
use OctoLab\Cilex\Doctrine\Util\Parser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CheckMigrationCommand extends AbstractCommand
{
    protected function configure()
    {
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
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migration = $input->getArgument('migration');
        $parser = new Parser();
        if (is_file($migration)) {
            $queries = $parser->extractSql(file_get_contents($migration));
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
            $class = $configuration->getMigrationsNamespace() . '\Version' . $migration;
        } elseif (class_exists($migration)) {
            $class = $migration;
        } else {
            throw new \InvalidArgumentException('Migration must be a valid file or version or class.');
        }
        $reflection = new \ReflectionClass($class);
        $instance = $reflection->newInstanceWithoutConstructor();
        if ($instance instanceof FileBasedMigration) {
            $properties = ['upgrade', 'downgrade'];
            foreach ($properties as $name) {
                try {
                    $property = $reflection->getProperty($name);
                    $property->setAccessible(true);
                    $files = $property->getValue($instance);
                    if ($files) {
                        $output->writeln(sprintf('<comment>%s for migration %s</comment>', ucfirst($name), $migration));
                        foreach ($files as $file) {
                            $queries = $parser->extractSql(file_get_contents($instance->getFullPath($file)));
                            $this->printQueries($queries, $output);
                        }
                    }
                } catch (\ReflectionException $e) {
                    throw new \RuntimeException('', 0, $e);
                }
            }
        } else {
            throw new \RuntimeException(sprintf(
                'Migration must be an instance of %s. Use "--dry-run" option of %s to see its content instead.',
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
