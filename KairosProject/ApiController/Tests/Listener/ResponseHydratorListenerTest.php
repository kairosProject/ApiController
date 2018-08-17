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
 * @category Api_Controller_Listener_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Tests\Listener;

use KairosProject\Tests\AbstractTestClass;
use KairosProject\ApiController\Listener\ResponseHydratorListener;
use KairosProject\ApiController\Event\ProcessEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Response hydrator listener test
 *
 * This test validate the ResponseHydratorListener class.
 *
 * @category Api_Controller_Listener_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ResponseHydratorListenerTest extends AbstractTestClass
{
    /**
     * Constructor method test.
     *
     * Validate the KairosProject\ApiController\Listener\ResponseHydratorListener::__construct method
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertConstructor(['parameterName' => 'name']);
        $this->assertConstructor([], ['parameterName' => ResponseHydratorListener::ORIGINAL_DATA_PARAMETER]);
    }

    /**
     * HydrateResponse method test.
     *
     * Validate the KairosProject\ApiController\Listener\ResponseHydratorListener::hydrateResponse method
     *
     * @return void
     */
    public function testHydrateResponse()
    {
        $parameterName = 'paramName';
        $parameter = $this->createMock(\stdClass::class);

        $event = $this->createMock(ProcessEventInterface::class);
        $this->getInvocationBuilder($event, $this->once(), 'hasParameter')
            ->with($this->equalTo($parameterName))
            ->willReturn(true);

        $this->getInvocationBuilder($event, $this->once(), 'getParameter')
            ->with($this->equalTo($parameterName))
            ->willReturn($parameter);

        $this->getInvocationBuilder($event, $this->once(), 'setResponse')
            ->with($this->identicalTo($parameter));

        $instance = $this->getInstance(['parameterName' => $parameterName]);

        $instance->hydrateResponse(
            $event,
            'event_name',
            $this->createMock(EventDispatcherInterface::class)
        );
    }

    /**
     * HydrateResponse method test.
     *
     * Validate the KairosProject\ApiController\Listener\ResponseHydratorListener::hydrateResponse method in case of
     * irrelevant parameter name
     *
     * @return void
     */
    public function testHydrateResponseError()
    {
        $parameterName = 'paramName';

        $event = $this->createMock(ProcessEventInterface::class);
        $this->getInvocationBuilder($event, $this->once(), 'hasParameter')
            ->with($this->equalTo($parameterName))
            ->willReturn(false);

        $instance = $this->getInstance(['parameterName' => $parameterName]);

        $this->expectException(\LogicException::class);

        $message = 'The specified parameter "paramName" does not exist in the event parameter bag.';
        $message .= 'This can be due to an unplugged listener or even an miscatched exception.';
        $this->expectExceptionMessage($message);

        $instance->hydrateResponse(
            $event,
            'event_name',
            $this->createMock(EventDispatcherInterface::class)
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
        return ResponseHydratorListener::class;
    }
}
