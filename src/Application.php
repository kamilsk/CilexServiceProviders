<?php

declare(strict_types = 1);

namespace OctoLab\Cilex;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Application extends \Cilex\Application
{
    /** @var array<string,bool> */
    private $registry = [];

    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function register($provider, array $values = [])
    {
        $key = \get_class($provider);
        if (!isset($this->registry[$key])) {
            parent::register($provider, $values);
            $this->registry[$key] = true;
        }
    }
}
