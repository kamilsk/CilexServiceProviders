<?php

namespace OctoLab\Cilex\Command;

use PhpSchool\CliMenu\CliMenuBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class PresetCommand extends Command
{
    /** @var CliMenuBuilder */
    private $menu;
    /** @var array */
    private $callbacks = [];

    /**
     * @param string $path Is a key or path in a special format (e.g. "some:component:config") of cli menu configuration
     * @param array $default Default cli menu configuration
     *
     * @return array
     *
     * @throws \RuntimeException
     *
     * @api
     */
    public function getConfig($path = 'cli_menu', $default = [])
    {
        $config = parent::getConfig($path, $default);
        if (!is_array($config) || empty($config)) {
            throw new \RuntimeException(sprintf('Command "%s" is not configured.', $this->getName()));
        }
        return $config;
    }

    /**
     * @param string $item
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     *
     * @api
     */
    public function runMenuItem($item, InputInterface $input, OutputInterface $output)
    {
        $this->initMenu($input, $output);
        if (isset($this->callbacks[$item])) {
            return $this->callbacks[$item]();
        }
        throw new \RuntimeException(sprintf('Item "%s" not found.', $item));
    }

    /**
     * @inheritDoc
     *
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('menu');
    }

    /**
     * @inheritDoc
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initMenu($input, $output)->build()->open();
        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return CliMenuBuilder
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function initMenu(InputInterface $input, OutputInterface $output)
    {
        if ($this->menu === null) {
            $config = $this->getConfig();
            $this->menu = (new CliMenuBuilder())
                ->setTitle($config['title'])
            ;
            foreach ($config['items'] as $item) {
                $key = $item['text'];
                $this->callbacks[$key] = function () use ($item, $input, $output) {
                    $command = $this->getApplication()->get($item['callable']);
                    $property = (new \ReflectionObject($input))->getProperty('tokens');
                    $property->setAccessible(true);
                    $property->setValue($input, []);
                    $input->bind($command->getDefinition());
                    foreach (['arguments' => 'setArgument', 'options' => 'setOption'] as $type => $setter) {
                        if (isset($item[$type]) && is_array($item[$type])) {
                            foreach ($item[$type] as $name => $value) {
                                $input->{$setter}($name, $value);
                            }
                        }
                    }
                    return $command->execute($input, $output);
                };
                $this->menu->addItem($key, $this->callbacks[$key]);
            }
        }
        return $this->menu;
    }
}
