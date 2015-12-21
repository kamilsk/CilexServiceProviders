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
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;
    /** @var array */
    private $callbacks = [];

    /**
     * @return array
     *
     * @throws \RuntimeException
     */
    public function getConfig()
    {
        $config = parent::getConfig();
        if (empty($config['cli_menu']) || !is_array($config['cli_menu'])) {
            throw new \RuntimeException(sprintf('Command "%s" is not configured.', $this->getName()));
        }
        return $config['cli_menu'];
    }

    /**
     * @param InputInterface $input
     *
     * @return $this
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * @param OutputInterface $output
     *
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->initMenu()->setInput($input)->setOutput($output);
        return parent::run($input, $output);
    }

    /**
     * @param string $item
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function runMenuItem($item, InputInterface $input, OutputInterface $output)
    {
        $this->initMenu()->setInput($input)->setOutput($output);
        if (isset($this->callbacks[$item])) {
            return $this->callbacks[$item]();
        }
        throw new \RuntimeException(sprintf('Item "%s" not found.', $item));
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('menu');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PhpSchool\CliMenu\Exception\InvalidTerminalException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->menu->build()->open();
        return 0;
    }

    /**
     * @return $this
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    private function initMenu()
    {
        if (!$this->menu) {
            $config = $this->getConfig();
            $this->menu = (new CliMenuBuilder())
                ->setTitle($config['title'])
            ;
            foreach ($config['items'] as $item) {
                $key = $item['text'];
                $this->callbacks[$key] = function () use ($item) {
                    $command = $this->getApplication()->get($item['callable']);
                    $property = (new \ReflectionObject($this->input))->getProperty('tokens');
                    $property->setAccessible(true);
                    $property->setValue($this->input, []);
                    $this->input->bind($command->getDefinition());
                    if (isset($item['arguments']) && is_array($item['arguments'])) {
                        foreach ($item['arguments'] as $name => $value) {
                            $this->input->setArgument($name, $value);
                        }
                    }
                    if (isset($item['options']) && is_array($item['options'])) {
                        foreach ($item['options'] as $name => $value) {
                            $this->input->setOption($name, $value);
                        }
                    }
                    return $command->execute($this->input, $this->output);
                };
                $this->menu->addItem($key, $this->callbacks[$key]);
            }
        }
        return $this;
    }
}
