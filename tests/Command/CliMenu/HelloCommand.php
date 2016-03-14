<?php

namespace OctoLab\Cilex\Command\CliMenu;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class HelloCommand extends Command
{
    protected function configure()
    {
        $this->setName('hello')->addArgument('message', InputArgument::REQUIRED, 'Welcome message.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Hello, %s', $input->getArgument('message')));
        return 0;
    }
}
