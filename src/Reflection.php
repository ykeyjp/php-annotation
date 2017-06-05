<?php
namespace ykey\annotation;

/**
 * Class Reflection
 *
 * @package ykey\annotation
 */
class Reflection
{
    /**
     * @param string $className
     *
     * @return Reflection
     */
    public static function fromClassName(string $className)
    {
        $ref = new \ReflectionClass($className);
        $annotation = new self;
        $annotation->classAnnotation = new Collection(DocParser::fromString($ref->getDocComment()));
        foreach ($ref->getProperties() as $property) {
            $annotation->propAnnotations[$property->getName()] =
                new Collection(DocParser::fromString($property->getDocComment()));
        }
        foreach ($ref->getMethods() as $method) {
            $annotation->methodAnnotations[$method->getName()] =
                new Collection(DocParser::fromString($method->getDocComment()));
        }

        return $annotation;
    }

    /**
     * @var Collection
     */
    private $classAnnotation;
    /**
     * @var Collection[]
     */
    private $propAnnotations;
    /**
     * @var Collection[]
     */
    private $methodAnnotations;

    /**
     * Reflection constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return Collection
     */
    public function getClassAnnotation(): Collection
    {
        return $this->classAnnotation;
    }

    /**
     * @return Collection[]
     */
    public function getPropertyAnnotations(): array
    {
        return $this->propAnnotations;
    }

    /**
     * @param string $name
     *
     * @return null|Collection
     */
    public function getPropertyAnnotation(string $name): ?Collection
    {
        return $this->propAnnotations[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasPropertyAnnotation(string $name): bool
    {
        return isset($this->propAnnotations[$name]);
    }

    /**
     * @return Collection[]
     */
    public function getMethodAnnotations(): array
    {
        return $this->methodAnnotations;
    }

    /**
     * @param string $name
     *
     * @return null|Collection
     */
    public function getMethodAnnotation(string $name): ?Collection
    {
        return $this->methodAnnotations[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasMethodAnnotation(string $name): bool
    {
        return isset($this->methodAnnotations[$name]);
    }
}
