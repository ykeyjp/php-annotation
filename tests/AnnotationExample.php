<?php
namespace ykey\annotation;

/**
 * Class AnnotationExample
 *
 * @package ykey\annotation
 * @Test1
 * @Test2("test", name: 'test')
 * @Test3([name1: "a", name2: "b"])
 */
class AnnotationExample
{
    /**
     * @Test1
     * @Test2("test", name: 'test')
     * @Test3([name1: "a", name2: "b"])
     */
    public $property1;

    /**
     * @Test1
     * @Test2("test", name: 'test')
     * @Test3([name1: "a", name2: "b"])
     */
    public function method1()
    {
    }
}
