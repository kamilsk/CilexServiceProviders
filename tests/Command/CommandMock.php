<?php

namespace OctoLab\Cilex\Command;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandMock extends Command
{
    protected function configure()
    {
        $this->setName('mock');
    }
}