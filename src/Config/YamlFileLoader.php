<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Symfony\Component\DependencyInjection\Loader\YamlFileLoader
 */
class YamlFileLoader extends FileLoader
{
    /** @var array */
    private $content = [];
    /** @var YamlParser */
    private $yamlParser;

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $resource
     * @param string $type
     */
    public function load($resource, $type = null)
    {
        $path = $this->locator->locate($resource);
        $content = $this->loadFile($path);
        if (null === $content) {
            return;
        }
        $this->content[] = $content;
        $this->parseImports($content, $path);
    }

    /**
     * @param mixed $resource
     * @param string $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && !strcasecmp('yml', pathinfo($resource, PATHINFO_EXTENSION));
    }

    /**
     * @param string $file
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function loadFile($file)
    {
        if (!stream_is_local($file)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a local file.', $file));
        }
        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a regular file.', $file));
        }
        if (!is_readable($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" is not readable.', $file));
        }
        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }
        return $this->yamlParser->parse(file_get_contents($file));
    }

    /**
     * @param array $content
     * @param string $sourceResource
     */
    protected function parseImports($content, $sourceResource)
    {
        if (!isset($content['imports'])) {
            return;
        }
        $this->setCurrentDir(dirname($sourceResource));
        foreach ($content['imports'] as $import) {
            if (isset($import['resource'])) {
                $ignoreErrors = isset($import['ignore_errors']) ? boolval($import['ignore_errors']) : false;
                $this->import($import['resource'], null, $ignoreErrors, $sourceResource);
            }
        }
    }
}
