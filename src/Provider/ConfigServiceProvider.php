<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Cilex\Config\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\ConfigServiceProvider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /** @var string */
    protected $filename;
    /** @var array */
    protected $placeholders;

    /**
     * @param string $filename
     * @param array $placeholders
     */
    public function __construct($filename, array $placeholders = [])
    {
        $this->filename = $filename;
        $this->placeholders = $placeholders;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $file = $this->filename;
        $placeholders = $this->placeholders;
        $app['array_merge_recursive'] = $app->protect(function (array $base) use ($app) {
            $mixtures = array_slice(func_get_args(), 1);
            foreach ($mixtures as $mixture) {
                foreach ($mixture as $key => $value) {
                    if (isset($base[$key]) && is_array($base[$key]) && is_array($value)) {
                        $base[$key] = $app['array_merge_recursive']($base[$key], $value);
                    } else {
                        $base[$key] = $value;
                    }
                }
            }
            return $base;
        });
        $app['array_transform_recursive'] = $app->protect(function (array & $array, array $placeholders) {
            array_walk_recursive($array, function (&$param) use ($placeholders) {
                if (preg_match('/^%(.+)%$/', $param, $matches)) {
                    $placeholder = $matches[1];
                    if (isset($placeholders[$placeholder])) {
                        $param = $placeholders[$placeholder];
                    }
                }
            });
        });
        $app['config'] = $app->share(function () use ($app, $file, $placeholders) {
            $loader = new YamlFileLoader(new FileLocator());
            switch (true) {
                case $loader->supports($file):
                    $loader->load($file);
                    break;
                default:
                    throw new \RuntimeException(sprintf('File "%s" is not supported.', $file));
            }
            $config = [];
            foreach (array_reverse($loader->getContent()) as $data) {
                $config = $app['array_merge_recursive']($config, $data);
            }
            if (isset($config['parameters'])) {
                $app['array_transform_recursive']($config['parameters'], $placeholders);
                $placeholders = array_merge($config['parameters'], $placeholders);
            }
            $app['array_transform_recursive']($config, $placeholders);
            unset($config['parameters'], $config['imports']);
            return $config;
        });
    }
}
