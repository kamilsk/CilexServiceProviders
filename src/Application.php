<?php

namespace OctoLab\Cilex;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Application extends \Cilex\Application
{
    /** @var array */
    private $registry = [];

    /**
     * {@inheritdoc}
     */
    public function register($provider, array $values = [])
    {
        $key = get_class($provider);
        if (!isset($this->registry[$key])) {
            parent::register($provider, $values);
            $this->registry[$key] = true;
        }
    }
}
