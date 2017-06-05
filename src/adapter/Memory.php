<?php
namespace ykey\annotation\adapter;

use ykey\annotation\AdapterInterface;
use ykey\annotation\AdapterTrait;
use ykey\annotation\Reflection;

/**
 * Class Memory
 *
 * @package ykey\annotation\adapter
 */
class Memory implements AdapterInterface
{
    use AdapterTrait;

    /**
     * @var Reflection[]
     */
    private $caches = [];

    /**
     * @param string $className
     *
     * @return null|Reflection
     */
    protected function loadFromCache(string $className): ?Reflection
    {
        return $this->caches[$className] ?? null;
    }

    /**
     * @param string     $className
     * @param Reflection $reflection
     */
    protected function saveToCache(string $className, Reflection $reflection): void
    {
        $this->caches[$className] = $reflection;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function hasCache(string $className): bool
    {
        return isset($this->caches[$className]);
    }
}
