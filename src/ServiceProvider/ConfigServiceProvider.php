<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Common\Config\FileConfig;
use OctoLab\Common\Config\Loader\FileLoader;
use OctoLab\Common\Config\Loader\Parser\JsonParser;
use OctoLab\Common\Config\Loader\Parser\YamlParser;
use OctoLab\Common\Config\SimpleConfig;
use Symfony\Component\Config\FileLocator;

/**
 * @see \Cilex\Provider\ConfigServiceProvider
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /** @var string */
    private $filename;
    /** @var array */
    private $placeholders;

    /**
     * @param string $filename
     * @param array $placeholders
     *
     * @api
     */
    public function __construct($filename, array $placeholders = [])
    {
        $this->filename = $filename;
        $this->placeholders = $placeholders;
    }

    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \DomainException
     *
     * @api
     */
    public function register(Application $app)
    {
        $app['config'] = $app::share(function () {
            switch (strtolower(pathinfo($this->filename, PATHINFO_EXTENSION))) {
                case 'yml':
                    $config = (new FileConfig(new FileLoader(new FileLocator(), new YamlParser())))
                        ->load($this->filename, $this->placeholders)
                    ;
                    break;
                case 'php':
                    $config = (new SimpleConfig(include $this->filename, $this->placeholders));
                    break;
                case 'json':
                    $config = (new FileConfig(new FileLoader(new FileLocator(), new JsonParser())))
                        ->load($this->filename, $this->placeholders)
                    ;
                    break;
                default:
                    throw new \DomainException(sprintf('File "%s" is not supported.', $this->filename));
            }
            return $config;
        });
    }
}
