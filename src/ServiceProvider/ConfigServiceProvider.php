<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Cilex\Command\Config\DumpCommand;
use OctoLab\Kilex\ServiceProvider\ConfigServiceProvider as KilexConfigServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProvider extends KilexConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \DomainException
     * @throws \LogicException
     *
     * @api
     */
    public function register(Application $app)
    {
        $this->setup($app);
        $app->command(new DumpCommand('config'));
    }
}
