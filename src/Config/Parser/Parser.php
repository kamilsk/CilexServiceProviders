<?php

namespace OctoLab\Cilex\Config\Parser;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
interface Parser
{
    /**
     * @param string $content
     *
     * @return mixed
     *
     * @api
     */
    public function parse($content);
}
