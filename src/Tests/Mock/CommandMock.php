<?php

namespace OctoLab\Cilex\Tests\Mock;

use OctoLab\Cilex\Command\Command;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandMock extends Command
{
    protected function configure()
    {
        $this->setName('test');
    }
}
