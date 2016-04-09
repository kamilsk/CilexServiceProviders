<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Kilex\ServiceProvider\MonologServiceProvider as KilexMonologServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProvider extends KilexMonologServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function register(Application $app)
    {
        $this->setup($app);
        $app['app.name'] = $app['console.name'];
    }
}
