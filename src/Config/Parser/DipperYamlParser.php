<?php

namespace OctoLab\Cilex\Config\Parser;

use secondparty\Dipper\Dipper;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DipperYamlParser implements Parser
{
    /**
     * {@inheritdoc}
     */
    public function parse($content)
    {
        return Dipper::parse($content);
    }
}
