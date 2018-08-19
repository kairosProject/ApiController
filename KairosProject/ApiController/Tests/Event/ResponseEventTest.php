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
use KairosProject\ApiController\Event\ResponseEvent;

/**
 * Response event test
 *
 * This test validate the ResponseEvent class.
 *
 * @category Api_Controller_Event_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ResponseEventTest extends AbstractTestClass
{
    /**
     * Test for response accessor.
     *
     * Validate the KairosProject\ApiController\Event\ResponseEvent::setResponse and
     * KairosProject\ApiController\Event\ResponseEvent::getResponse methods.
     *
     * @return void
     */
    public function testResponseAccessor()
    {
        $this->assertHasSimpleAccessor('response', $this->createMock(\stdClass::class));
    }

    /**
     * Test for setParameter.
     *
     * Validate the KairosProject\ApiController\Event\ResponseEvent::setParameter method
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
     * Test for setParameters.
     *
     * Validate the KairosProject\ApiController\Event\ResponseEvent::setParameters method
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
        return ResponseEvent::class;
    }
}
