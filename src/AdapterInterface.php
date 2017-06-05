<?php
namespace ykey\annotation;

/**
 * Interface AdapterInterface
 *
 * @package ykey\annotation
 */
interface AdapterInterface
{
    /**
     * @param string $className
     *
     * @return null|Reflection
     */
    public function get(string $className): ?Reflection;
}
