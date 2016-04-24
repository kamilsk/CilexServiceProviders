<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\CliMenu;

use OctoLab\Cilex\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class PresetCommand extends Command
{
    /** @var MenuBuilder|null */
    private $menuBuilder;
    /** @var bool */
    private $isConfigured = false;

    /**
     * @param string $item
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function runMenuItem(string $item, InputInterface $input, OutputInterface $output): int
    {
        return call_user_func($this->getMenuBuilder($input, $output)->getItemCallback($item));
    }

    /**
     * @param MenuBuilder $menuBuilder
     * @param bool $isConfigured
     *
     * @return PresetCommand
     */
    public function setMenuBuilder(MenuBuilder $menuBuilder, bool $isConfigured = false): PresetCommand
    {
        $this->menuBuilder = $menuBuilder;
        $this->isConfigured = $isConfigured;
        return $this;
    }

    protected function configure()
    {
        $this
            ->setName('menu')
            ->setDescription('Show the preset menu of commands.')
            ->addOption('dump', null, InputOption::VALUE_NONE, 'Output configured commands.')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Configuration path.', 'cli_menu')
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $builder = $this->getMenuBuilder($input, $output);
        if ($input->getOption('dump')) {
            $this->dumpCommands($builder, $output);
        } else {
            $builder->build()->open();
        }
        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return MenuBuilder
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function getMenuBuilder(InputInterface $input, OutputInterface $output): MenuBuilder
    {
        $this->menuBuilder = $this->menuBuilder ?? new MenuBuilder();
        if (!$this->isConfigured) {
            $config = $this->getConfig($input->getOption('path'));
            $this->menuBuilder->setTitle($config['title']);
            foreach ($config['items'] as $item) {
                $this->menuBuilder->addItem($item['text'], $this->getCallback($item, $output));
            }
            $this->isConfigured = true;
        }
        return $this->menuBuilder;
    }

    /**
     * @param array $item
     * @param OutputInterface $output
     *
     * @return \Closure
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function getCallback(array $item, OutputInterface $output): \Closure
    {
        return isset($item['commands'])
            ? $this->getBatchCommandsCallback($item['commands'], $output)
            : $this->getSingleCommandCallback($item, $output);
    }

    /**
     * @param array $items
     * @param OutputInterface $output
     *
     * @return \Closure
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function getBatchCommandsCallback(array $items, OutputInterface $output): \Closure
    {
        return function (bool $dump = false) use ($items, $output) {
            $result = [];
            foreach ($items as $item) {
                $command = $this->getApplication()->get($item['name']);
                $input = $this->getArgvIntputForMenuItem($command->getDefinition(), $item);
                $result[] = $dump
                    ? sprintf('%s %s', $command->getName(), $input)
                    : (int)$command->execute($input, $output);
            }
            return $dump ? $result : max(...$result);
        };
    }

    /**
     * @param array $item
     * @param OutputInterface $output
     *
     * @return \Closure
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function getSingleCommandCallback(array $item, OutputInterface $output): \Closure
    {
        return function (bool $dump = false) use ($item, $output) {
            $command = $this->getApplication()->get($item['callable']);
            $input = $this->getArgvIntputForMenuItem($command->getDefinition(), $item);
            return $dump ? [sprintf('%s %s', $command->getName(), $input)] : (int)$command->execute($input, $output);
        };
    }

    /**
     * @param InputDefinition $definition
     * @param array $item
     *
     * @return ArgvInput
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function getArgvIntputForMenuItem(InputDefinition $definition, array $item): ArgvInput
    {
        $argv = [null];
        $argv = array_merge($argv, $this->getOptions($definition, $item['options'] ?? []));
        $argv = array_merge($argv, $this->getArguments($definition, $item['arguments'] ?? []));
        return new ArgvInput($argv, $definition);
    }

    /**
     * @param InputDefinition $definition
     * @param array $options
     *
     * @return array
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function getOptions(InputDefinition $definition, array $options): array
    {
        $argv = [];
        foreach ($options as $name => $value) {
            $definition->getOption($name) && $argv[] = sprintf('--%s=%s', $name, $value);
        }
        return $argv;
    }

    /**
     * @param InputDefinition $definition
     * @param array $arguments
     *
     * @return array
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    private function getArguments(InputDefinition $definition, array $arguments): array
    {
        $argv = [];
        foreach ($arguments as $name => $value) {
            $definition->getArgument($name) && $argv[] = $value;
        }
        return $argv;
    }

    /**
     * @param MenuBuilder $builder
     * @param OutputInterface $output
     */
    private function dumpCommands(MenuBuilder $builder, OutputInterface $output)
    {
        $commands = $builder->getItemCallbacks();
        $output->writeln(sprintf('Total commands: %d', count($commands)));
        foreach ($commands as $command) {
            $result = $command(true);
            array_walk($result, function ($entry) use ($output) {
                $output->writeln(' - ' . $entry);
            });
            $output->writeln('');
        }
    }
}
