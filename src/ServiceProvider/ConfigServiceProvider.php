<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Cilex\Command\Config\DumpCommand;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProvider
    extends \OctoLab\Kilex\ServiceProvider\ConfigServiceProvider
    implements ServiceProviderInterface
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
        parent::init($app);
        $app->command(new DumpCommand('config'));
    }
}
