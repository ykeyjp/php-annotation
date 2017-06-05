<?php
namespace ykey\annotation\adapter;

use ykey\annotation\AdapterInterface;
use ykey\annotation\AdapterTrait;
use ykey\annotation\Reflection;

/**
 * Class APCu
 *
 * @package ykey\annotation\adapter
 */
class APCu implements AdapterInterface
{
    use AdapterTrait;

    /**
     * @var string
     */
    private $prefix;
    /**
     * @var int
     */
    private $ttl;

    /**
     * APCu constructor.
     *
     * @param string $prefix
     * @param int    $ttl
     */
    public function __construct(string $prefix, int $ttl = 3600)
    {
        if (!extension_loaded('apcu')) {
            throw new \RuntimeException('not loaded APCu extension.');
        }
        $this->prefix = $prefix;
        $this->ttl = $ttl;
    }

    /**
     * @param string $className
     *
     * @return null|Reflection
     */
    protected function loadFromCache(string $className): ?Reflection
    {
        return apcu_fetch($className) ?? null;
    }

    /**
     * @param string     $className
     * @param Reflection $reflection
     */
    protected function saveToCache(string $className, Reflection $reflection): void
    {
        apcu_store($this->prefix . '#' . $className, $reflection, $this->ttl);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function hasCache(string $className): bool
    {
        return apcu_exists($this->prefix . '#' . $className);
    }
}
