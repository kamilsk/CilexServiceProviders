<?php

namespace Test\OctoLab\Cilex\Command\Mock;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class FibonacciCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('fibonacci')->addOption('size', null, InputOption::VALUE_OPTIONAL, 'Sequence size', 100);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $size = $input->getOption('size');
        $sequence = [];
        foreach ($this->getFibonacciSequence() as $number) {
            $sequence[] = $number;
            if (count($sequence) >= $size) {
                break;
            }
        }
        $output->writeln(sprintf('Fibonacci sequence: %s', implode(', ', $sequence)));
        return 0;
    }

    /**
     * @return \Generator
     */
    private function getFibonacciSequence()
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
