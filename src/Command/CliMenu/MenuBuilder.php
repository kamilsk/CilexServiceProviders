<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\CliMenu;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MenuBuilder extends \PhpSchool\CliMenu\CliMenuBuilder
{
    /** @var array<string,callable> */
    private $callbacks = [];

    /**
     * @param string $text
     * @param callable $itemCallable
     * @param bool $showItemExtra
     *
     * @return MenuBuilder
     *
     * @api
     */
    public function addItem($text, callable $itemCallable, $showItemExtra = false): MenuBuilder
    {

        $this->callbacks[$this->resolveKey($text)] = $itemCallable;
        return parent::addItem($text, $itemCallable, $showItemExtra);
    }

    /**
     * @param string $text
     *
     * @return callable
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function getItemCallback(string $text): callable
    {
        $key = $this->resolveKey($text);
        if (isset($this->callbacks[$key])) {
            return $this->callbacks[$key];
        }
        throw new \InvalidArgumentException(sprintf('Callback for item "%s" not found.', $text));
    }

    /**
     * @return array<string,callable>
     *
     * @api
     */
    public function getItemCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function resolveKey(string $text): string
    {
        return md5($text);
    }
}
