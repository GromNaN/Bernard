<?php

namespace Bernard\Tests\ServiceResolver;

use Bernard\ServiceResolver\ObjectResolver;
use Bernard\ServiceResolver\Invocator;

class ObjectResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsServiceResolver()
    {
        $this->assertInstanceOf('Bernard\ServiceResolver', new ObjectResolver);
    }

    /**
     * @dataProvider dataProviderNotObjects
     */
    public function testItThrowsExceptionWhenServiceIsNotAnObject($name, $type)
    {
        $this->setExpectedException('InvalidArgumentException');
        $resolver = new ObjectResolver;
        $resolver->register($name, $type);
    }

    public function testItResolvesBasedOnMessageName()
    {
        $message = $this->getMock('Bernard\Message');
        $message->expects($this->any())->method('getName')->will($this->returnValue('SendNewsletter'));

        $service = new \stdClass;

        $resolver = new ObjectResolver;
        $resolver->register('SendNewsletter', $service);

        $this->assertEquals(new Invocator($service, $message), $resolver->resolve($message));
    }

    public function testItThrowsExceptionIfServiceCannotBeFound()
    {
        $this->setExpectedException('InvalidArgumentException');

        $message = $this->getMock('Bernard\Message');
        $message->expects($this->any())->method('getName')->will($this->returnValue('SendNewsletter'));

        $resolver = new ObjectResolver;
        $resolver->resolve($message);
    }

    public function dataProviderNotObjects()
    {
        return array(
            array('SendNewsletter', 'string'),
            array('SendNewsletter', true),
            array('SendNewsletter', false),
            array('SendNewsletter', 1.02),
            array('SendNewsletter', 12),
        );
    }
}
