<?php
namespace ykey\annotation;

/**
 * Class Annotation
 *
 * @package ykey\annotation
 */
class Annotation
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $arguments;

    /**
     * Annotation constructor.
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __construct(string $name, array $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
