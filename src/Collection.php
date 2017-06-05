<?php
namespace ykey\annotation;

/**
 * Class Collection
 *
 * @package ykey\annotation
 */
class Collection implements \Iterator, \Countable
{
    /**
     * @var Annotation[]
     */
    private $annotations;
    /**
     * @var int
     */
    private $position = 0;

    /**
     * Collection constructor.
     *
     * @param Annotation[] $annotations
     */
    public function __construct(array $annotations = [])
    {
        $this->annotations = $annotations;
    }

    /**
     * @return Annotation[]
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * @param string $name
     *
     * @return null|Annotation
     */
    public function get(string $name): ?Annotation
    {
        foreach ($this->annotations as $annotation) {
            if ($annotation->getName() === $name) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return Annotation[]
     */
    public function getAll(string $name): array
    {
        return array_filter($this->annotations, function (Annotation $annotation) use ($name) {
            return $annotation->getName() === $name;
        });
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return !is_null($this->get($name));
    }

    /**
     * @return mixed|bool|Annotation
     */
    public function current(): mixed
    {
        if (isset($this->annotations[$this->position])) {
            return $this->annotations[$this->position];
        }

        return false;
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->annotations[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->annotations);
    }
}
