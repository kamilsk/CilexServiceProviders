<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\CliMenu;

use Cilex\Application;
use OctoLab\Cilex\Command\Command;
use OctoLab\Cilex\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class PresetCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function runMenuItem(Application $app)
    {
        $app->register($this->getConfigServiceProviderForCliMenu());
        $app->command($command = $this->getPresetCommand());
        $app->command($this->getHelloCommand());
        $app->command($this->getFibonacciCommand());
        $command->setMenuBuilder($this->getMenuBuilder());
        $input = new ArgvInput([], $command->getDefinition());
        $output = $this->getBufferedOutput();
        self::assertEquals(0, $command->runMenuItem('Hello, World', $input, $output));
        self::assertContains('Hello, World', $output->fetch());
        self::assertEquals(0, $command->runMenuItem('Fibonacci sequence', $input, $output));
        self::assertContains('Fibonacci sequence: 1, 1, 2, 3, 5, 8, 13, 21, 34, 55', $output->fetch());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function execute(Application $app)
    {
        $app->register($this->getConfigServiceProviderForCliMenu());
        $app->command($command = $this->getPresetCommand());
        $app->command($this->getHelloCommand());
        $app->command($this->getFibonacciCommand());
        $command->setMenuBuilder($this->getMenuBuilder());
        $reflection = (new \ReflectionObject($command))->getMethod('execute');
        $reflection->setAccessible(true);
        $output = $this->getBufferedOutput();
        $input = new ArgvInput([], $command->getDefinition());
        self::assertEquals(0, $reflection->invoke($command, $input, $output));
        $output->fetch();
        $input = new ArgvInput([$command->getName(), '--dump'], $command->getDefinition());
        self::assertEquals(0, $reflection->invoke($command, $input, $output));
        self::assertContains(
            "Total commands: 3\n - test:hello World\n\n - test:fibonacci --size=10\n\n - test:fibonacci --size=1\n",
            $output->fetch()
        );
    }

    /**
     * @return Command
     *
     * @throws \InvalidArgumentException
     */
    private function getFibonacciCommand(): Command
    {
        return new class('test') extends Command
        {
            protected function configure()
            {
                $this->setName('fibonacci')->addOption('size', 's', InputOption::VALUE_REQUIRED, 'Sequence size.', 100);
            }

            protected function execute(InputInterface $input, OutputInterface $output): int
            {
                $size = (int)$input->getOption('size');
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
        };
    }

    /**
     * @return Command
     *
     * @throws \InvalidArgumentException
     */
    private function getHelloCommand(): Command
    {
        return new class('test') extends Command
        {
            protected function configure()
            {
                $this->setName('hello')->addArgument('message', InputArgument::REQUIRED, 'Welcome message.');
            }

            protected function execute(InputInterface $input, OutputInterface $output): int
            {
                $output->writeln(sprintf('Hello, %s', $input->getArgument('message')));
                return 0;
            }
        };
    }

    /**
     * @return PresetCommand
     *
     * @throws \LogicException
     */
    private function getPresetCommand(): PresetCommand
    {
        return new PresetCommand('test');
    }
}
