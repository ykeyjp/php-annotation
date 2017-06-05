<?php
namespace ykey\annotation;

use PHPUnit\Framework\TestCase;

class AnnotationTest extends TestCase
{
    public function testClass()
    {
        $adapter = new adapter\Memory;
        $reflection = $adapter->get(AnnotationExample::class);
        $annotation = $reflection->getClassAnnotation();
        $this->assertTrue($annotation->has('Test1'));
        $this->assertTrue($annotation->has('Test2'));
        $this->assertTrue($annotation->has('Test3'));
        $this->assertCount(2, $annotation->get('Test2')->getArguments());
        $this->assertSame(['test', 'name' => 'test'], $annotation->get('Test2')->getArguments());
        $this->assertSame([['name1' => 'a', 'name2' => 'b']], $annotation->get('Test3')->getArguments());
    }

    public function testProperty()
    {
        $adapter = new adapter\Memory;
        $reflection = $adapter->get(AnnotationExample::class);
        $this->assertTrue($reflection->hasPropertyAnnotation('property1'));
        $annotation = $reflection->getPropertyAnnotation('property1');
        $this->assertTrue($annotation->has('Test1'));
        $this->assertTrue($annotation->has('Test2'));
        $this->assertTrue($annotation->has('Test3'));
        $this->assertCount(2, $annotation->get('Test2')->getArguments());
        $this->assertSame(['test', 'name' => 'test'], $annotation->get('Test2')->getArguments());
        $this->assertSame([['name1' => 'a', 'name2' => 'b']], $annotation->get('Test3')->getArguments());
    }

    public function testMethod()
    {
        $adapter = new adapter\Memory;
        $reflection = $adapter->get(AnnotationExample::class);
        $this->assertTrue($reflection->hasMethodAnnotation('method1'));
        $annotation = $reflection->getMethodAnnotation('method1');
        $this->assertTrue($annotation->has('Test1'));
        $this->assertTrue($annotation->has('Test2'));
        $this->assertTrue($annotation->has('Test3'));
        $this->assertCount(2, $annotation->get('Test2')->getArguments());
        $this->assertSame(['test', 'name' => 'test'], $annotation->get('Test2')->getArguments());
        $this->assertSame([['name1' => 'a', 'name2' => 'b']], $annotation->get('Test3')->getArguments());
    }
}
