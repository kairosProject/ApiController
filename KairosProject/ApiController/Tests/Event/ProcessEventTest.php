<?php
declare(strict_types=1);
/**
 * This file is part of the kairos project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Api_Controller_Event_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Tests\Event;

use KairosProject\Tests\AbstractTestClass;
use KairosProject\ApiController\Event\ProcessEvent;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Process event test
 *
 * This test validate the ProcessEvent class.
 *
 * @category Api_Controller_Event_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ProcessEventTest extends AbstractTestClass
{
    /**
     * Constructor method test.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::__construct method
     *
     * @return void
     */
    public function testConstructor() : void
    {
        $this->assertConstructor(
            [
                'same:request' => $this->createMock(ServerRequestInterface::class)
            ],
            [
                'response' => null,
                'storage' => (new \ArrayObject())
            ]
        );
    }

    /**
     * Test for getRequest.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::getRequest method
     *
     * @return void
     */
    public function testGetRequest() : void
    {
        $this->assertIsSimpleGetter('request', 'getRequest', $this->createMock(ServerRequestInterface::class));
    }

    /**
     * Test for response accessor.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::setResponse and
     * KairosProject\ApiController\Event\ProcessEvent::getResponse methods.
     *
     * @return void
     */
    public function testResponseAccessor() : void
    {
        $this->assertHasSimpleAccessor('response', $this->createMock(\stdClass::class));
    }

    /**
     * Test for setParameter.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::setParameter method
     *
     * @return void
     */
    public function testSetParameter() : void
    {
        $parameterName = 'name';
        $parameterValue = $this->createMock(\stdClass::class);
        $instance = $this->getInstance(['storage' => new \ArrayObject()]);

        $this->assertSame(
            $instance,
            $instance->setParameter($parameterName, $parameterValue)
        );

        $storage = $this->getClassProperty('storage')->getValue($instance);

        $this->assertTrue($storage->offsetExists($parameterName));
        $this->assertSame($parameterValue, $storage->offsetGet($parameterName));
    }

    /**
     * Test for hasParameter.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::hasParameter method
     *
     * @return void
     */
    public function testHasParameter()
    {
        $parameterName = 'name';
        $parameterValue = $this->createMock(\stdClass::class);
        $instance = $this->getInstance(['storage' => new \ArrayObject([$parameterName => $parameterValue])]);

        $this->assertTrue($instance->hasParameter($parameterName));
        $this->assertFalse($instance->hasParameter('other_name'));
    }

    /**
     * Test for getParameters.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::getParameters method
     *
     * @return void
     */
    public function testGetParameters()
    {
        $parameters = [
            'a' => new \stdClass(),
            'b' => new \stdClass(),
            'c' => new \stdClass()
        ];
        $instance = $this->getInstance(['storage' => new \ArrayObject($parameters)]);

        $this->assertSame($parameters, $instance->getParameters());
    }

    /**
     * Test for getParameter.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::getParameter method
     *
     * @return void
     */
    public function testGetParameter()
    {
        $parameterName = 'name';
        $parameterValue = $this->createMock(\stdClass::class);
        $instance = $this->getInstance(['storage' => new \ArrayObject([$parameterName => $parameterValue])]);

        $this->assertSame($parameterValue, $instance->getParameter($parameterName));
        $this->assertNull($instance->getParameter('other_name'));
    }

    /**
     * Test for setParameters.
     *
     * Validate the KairosProject\ApiController\Event\ProcessEvent::setParameters method
     *
     * @return void
     */
    public function testSetParameters()
    {
        $instance = $this->getInstance(['storage' => new \ArrayObject()]);

        $initialParameters = [
            'a' => new \stdClass(),
            'b' => new \stdClass(),
            'c' => new \stdClass()
        ];
        $this->assertSame($instance, $instance->setParameters($initialParameters));
        $this->assertSame(
            $initialParameters,
            iterator_to_array(
                $this->getClassProperty('storage')
                    ->getValue($instance)
                    ->getIterator(),
                true
            )
        );

        $this->assertSame($instance, $instance->setParameters([]));
        $this->assertEmpty(
            iterator_to_array(
                $this->getClassProperty('storage')
                    ->getValue($instance)
                    ->getIterator(),
                true
            )
        );
    }

    /**
     * Get tested class
     *
     * Return the tested class name
     *
     * @return string
     */
    protected function getTestedClass() : string
    {
        return ProcessEvent::class;
    }
}
