<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\Doctrine;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class GenerateIndexNameCommand extends Command
{
    /**
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('generate-index-name')
            ->setDescription('Generate index name.')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Index type [uniq, fk, idx].', 'idx')
            ->addOption('table', 't', InputOption::VALUE_REQUIRED, 'Table name.')
            ->addOption('columns', 'c', InputOption::VALUE_REQUIRED, 'Columns separated by commas.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $availableTypes = ['uniq', 'fk', 'idx'];
        $type = strtolower($input->getOption('type'));
        if (!\in_array($type, $availableTypes, true)) {
            throw new \InvalidArgumentException(
                sprintf('Unknown type "%s", available types are "%s".', $type, implode('","', $availableTypes))
            );
        }
        $tableName = $input->getOption('table');
        $columns = explode(',', $input->getOption('columns'));
        $output->writeln(sprintf(
            'Index name: %s',
            $this->generateIdentifierName(array_merge([$tableName], $columns), $type)
        ));
        return 0;
    }

    /**
     * @param array $columnNames
     * @param string $prefix
     * @param int $maxSize
     *
     * @return string
     *
     * @see \Doctrine\DBAL\Schema\AbstractAsset::_generateIdentifierName
     * @see \Doctrine\DBAL\Schema\Table::_getMaxIdentifierLength
     */
    private function generateIdentifierName(array $columnNames, string $prefix = '', int $maxSize = 30): string
    {
        $hash = implode('', array_map(function ($column) {
            return dechex(crc32($column));
        }, $columnNames));
        return strtoupper(substr($prefix . '_' . $hash, 0, $maxSize));
    }
}
