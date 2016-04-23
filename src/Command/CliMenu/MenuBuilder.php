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
        $this->callbacks[$text] = $itemCallable;
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
    public function getItemCallback($text): callable
    {
        if (isset($this->callbacks[$text])) {
            return $this->callbacks[$text];
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
}
