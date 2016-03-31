<?php

namespace OctoLab\Cilex\Command\Config;

use Cilex\Application;
use OctoLab\Cilex\Command\Command;
use OctoLab\Cilex\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DumpCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function execute(Application $app)
    {
        $app->register($this->getConfigServiceProvider('config', 'yml'));
        /** @var Command $command */
        $command = $app['console']->get('config:dump');
        $reflection = (new \ReflectionObject($command))->getMethod('execute');
        $reflection->setAccessible(true);
        $output = new BufferedOutput();
        $input = new ArgvInput([], $command->getDefinition());
        $needle = <<<EOF
component:
    parameter: 'base component''s parameter will be overwritten by root config'
    base_parameter: 'base parameter will not be overwritten'
app:
    placeholder_parameter: test
    constant: 32767
EOF;
        self::assertEquals(0, $reflection->invoke($command, $input, $output));
        self::assertContains($needle, $output->fetch());
        $input = new ArgvInput([$command->getName(), '--path=app'], $command->getDefinition());
        $needle = <<<EOF
placeholder_parameter: test
constant: 32767
EOF;
        self::assertEquals(0, $reflection->invoke($command, $input, $output));
        self::assertContains($needle, $output->fetch());
    }
}
