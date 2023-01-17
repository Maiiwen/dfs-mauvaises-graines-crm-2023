<?php

namespace App\Model;

class ActionModel
{
    private string $route;
    private string $label;
    private string $icon;
    private string $color;

    /**
     * @param string $route
     * @param string $label
     * @param string $icon
     * @param string $color
     */
    public function __construct(string $route, string $label, string $icon, string $color = 'primary')
    {
        $this->route = $route;
        $this->label = $label;
        $this->icon = $icon;
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}