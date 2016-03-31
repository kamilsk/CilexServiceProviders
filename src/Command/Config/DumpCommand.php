<?php

namespace OctoLab\Cilex\Command\Config;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DumpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('dump')
            ->setDescription('Dump application configuration ($app["config"]).')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, null)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $path = $input->getOption('path')) {
            $config = $this->getConfig()->jsonSerialize();
        } else {
            $config = $this->getConfig($path);
        }
        $output->writeln(Yaml::dump($config));
        return 0;
    }
}
