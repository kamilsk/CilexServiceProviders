<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Command;

use OctoLab\Cilex\Command\Command;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function commandNamespace()
    {
        $command = new MockCommand();
        $this->assertEquals('test', $command->getName());
        $command = new MockCommand('mock');
        $this->assertEquals('mock:test', $command->getName());
    }
}

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MockCommand extends Command
{
    protected function configure()
    {
        $this->setName('test');
    }
}
