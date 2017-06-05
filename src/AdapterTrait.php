<?php
namespace ykey\annotation;

/**
 * Trait AdapterTrait
 *
 * @package ykey\annotation
 */
trait AdapterTrait
{
    /**
     * @param string $className
     *
     * @return Reflection
     */
    abstract protected function loadFromCache(string $className): Reflection;

    /**
     * @param string     $className
     * @param Reflection $reflection
     */
    abstract protected function saveToCache(string $className, Reflection $reflection): void;

    /**
     * @param string $className
     *
     * @return bool
     */
    abstract protected function hasCache(string $className): bool;

    /**
     * @param string $className
     *
     * @return null|Reflection
     */
    public function get(string $className): ?Reflection
    {
        if (!$this->hasCache($className)) {
            $refrection = Reflection::fromClassName($className);
            $this->saveToCache($className, $refrection);
        } else {
            $refrection = $this->loadFromCache($className);
        }

        return $refrection;
    }
}
