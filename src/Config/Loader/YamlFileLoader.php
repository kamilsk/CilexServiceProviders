<?php

namespace OctoLab\Cilex\Config\Loader;

use OctoLab\Cilex\Config\Parser\ParserInterface;
use OctoLab\Cilex\Config\Parser\SymfonyYamlParser;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Symfony\Component\DependencyInjection\Loader\YamlFileLoader
 */
class YamlFileLoader extends FileLoader
{
    /** @var array */
    private $content = [];
    /** @var ParserInterface */
    private $parser;

    /**
     * @param FileLocatorInterface $locator
     * @param ParserInterface $parser
     *
     * @api
     */
    public function __construct(FileLocatorInterface $locator, ParserInterface $parser = null)
    {
        parent::__construct($locator);
        // deprecated BC will be removed in v2.0, $parser will be required parameter
        if (null === $parser) {
            $parser = new SymfonyYamlParser();
        }
        $this->parser = $parser;
    }

    /**
     * @return array
     *
     * @api
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $resource
     * @param string $type
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     *
     * @api
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
     * @param string $resource
     * @param string $type
     *
     * @return bool
     *
     * @api
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && !strcasecmp('yml', pathinfo($resource, PATHINFO_EXTENSION));
    }

    /**
     * @param string $file
     *
     * @return mixed
     */
    private function loadFile($file)
    {
        return $this->parser->parse(file_get_contents($file));
    }

    /**
     * @param array $content
     * @param string $sourceResource
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     */
    private function parseImports($content, $sourceResource)
    {
        if (!isset($content['imports'])) {
            return;
        }
        $this->setCurrentDir(dirname($sourceResource));
        foreach ($content['imports'] as $import) {
            if (isset($import['resource'])) {
                $ignoreErrors = isset($import['ignore_errors']) ? (bool) $import['ignore_errors'] : false;
                $this->import($import['resource'], null, $ignoreErrors, $sourceResource);
            }
        }
    }
}
