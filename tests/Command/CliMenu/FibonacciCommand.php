<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\CliMenu;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class FibonacciCommand extends Command
{
    protected function configure()
    {
        $this->setName('fibonacci')->addOption('size', 's', InputOption::VALUE_REQUIRED, 'Sequence size.', 100);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $size = $input->getOption('size');
        $sequence = [];
        $i = 0;
        foreach ($this->getFibonacciSequence() as $number) {
            $sequence[] = $number;
            $i++;
            if ($i >= $size) {
                break;
            }
        }
        $output->writeln(sprintf('Fibonacci sequence: %s', implode(', ', $sequence)));
        return 0;
    }

    /**
     * @return \Generator
     */
    private function getFibonacciSequence(): \Generator
    {
        $i = 0;
        $k = 1;
        yield $k;
        while (true) {
            $k = $i + $k;
            $i = $k - $i;
            yield $k;
        }
    }
}
