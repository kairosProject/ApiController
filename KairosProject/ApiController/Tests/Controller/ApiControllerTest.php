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
namespace KairosProject\ApiController\Tests\Controller;

use KairosProject\Tests\AbstractTestClass;
use KairosProject\ApiController\Controller\ApiController;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use KairosProject\ApiController\Event\ProcessEvent;
use KairosProject\ApiController\Event\ResponseEvent;
use KairosProject\ApiController\Event\ResponseEventInterface;
use KairosProject\ApiController\Event\ProcessEventInterface;

/**
 * ApiController test
 *
 * This test validate the ApiController class.
 *
 * @category Api_Controller_Event_Test
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ApiControllerTest extends AbstractTestClass
{
    /**
     * Constructor method test.
     *
     * Validate the KairosProject\ApiController\Controller\ApiController::__construct method
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertConstructor(
            [
                'same:logger' => $this->createMock(LoggerInterface::class),
                'same:eventDispatcher' => $this->createMock(EventDispatcherInterface::class)
            ],
            [
                'processSuffix' => 'process_',
                'responseSuffix' => 'response_'
            ]
        );

        $this->assertConstructor(
            [
                'same:logger' => $this->createMock(LoggerInterface::class),
                'same:eventDispatcher' => $this->createMock(EventDispatcherInterface::class),
                'processSuffix' => 'specific_process_'
            ],
            [
                'responseSuffix' => 'response_'
            ]
        );

        $this->assertConstructor(
            [
                'same:logger' => $this->createMock(LoggerInterface::class),
                'same:eventDispatcher' => $this->createMock(EventDispatcherInterface::class),
                'processSuffix' => 'specific_process_',
                'responseSuffix' => 'specific_response_'
            ]
        );
    }

    /**
     * Test process with success.
     *
     * Validate the KairosProject\ApiController\Controller\ApiController::execute method
     *
     * @return void
     */
    public function testProcessSuccess()
    {
        $baseEvent = 'get';
        $responseEvent = $this->createMock(ResponseEventInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $processEvent = $this->createMock(ProcessEventInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $dispatcher = $this->createMock(EventDispatcher::class);

        $this->getInvocationBuilder($request, $this->once(), 'getMethod')
            ->willReturn('GET');

        $this->getInvocationBuilder($logger, $this->exactly(2), 'debug')
            ->withConsecutive(
                [
                    $this->equalTo('Processing started'),
                    $this->equalTo(
                        [
                            'Request method' => 'GET',
                            'Event name' => 'process_get',
                            'Controller' => $this->getTestedClass()
                        ]
                    )
                ],
                [
                    $this->equalTo('Rendering started'),
                    $this->equalTo(
                        [
                            'Event name' => 'response_get'
                        ]
                    )
                ]
            );

        $this->getInvocationBuilder($dispatcher, $this->exactly(2), 'dispatch')
            ->withConsecutive(
                [
                    $this->equalTo('process_get'),
                    $this->isInstanceOf(ProcessEvent::class)
                ],
                [
                    $this->equalTo('response_get'),
                    $this->isInstanceOf(ResponseEvent::class)
                ]
            )->willReturnOnConsecutiveCalls(
                $processEvent,
                $responseEvent
            );

        $this->getInvocationBuilder($processEvent, $this->once(), 'getResponse')
            ->willReturn($response);

        $this->getInvocationBuilder($responseEvent, $this->once(), 'getResponse')
            ->willReturn($response);

        $instance = $this->getInstance(
            [
                'logger' => $logger,
                'eventDispatcher' => $dispatcher,
                'processSuffix' => 'process_',
                'responseSuffix' => 'response_'
            ]
        );

        $this->assertSame($response, $instance->execute($request, $baseEvent));
    }

    /**
     * Test process with error.
     *
     * Validate the KairosProject\ApiController\Controller\ApiController::execute method in
     * case of exception throwing
     *
     * @return void
     */
    public function testProcessException()
    {
        $baseEvent = 'get';
        $responseEvent = $this->createMock(ResponseEventInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $dispatcher = $this->createMock(EventDispatcher::class);
        $exception = new \Exception();

        $this->getInvocationBuilder($request, $this->exactly(2), 'getMethod')
            ->willReturn('GET');

        $this->getInvocationBuilder($logger, $this->exactly(2), 'debug')
            ->withConsecutive(
                [
                    $this->equalTo('Processing started'),
                    $this->equalTo(
                        [
                            'Request method' => 'GET',
                            'Event name' => 'process_get',
                            'Controller' => $this->getTestedClass()
                        ]
                    )
                ],
                [
                    $this->equalTo('Rendering started'),
                    $this->equalTo(
                        [
                            'Event name' => 'response_get'
                        ]
                    )
                ]
            );

        $this->getInvocationBuilder($logger, $this->once(), 'error')
            ->withConsecutive(
                [
                    $this->equalTo('Process error'),
                    $this->equalTo(
                        [
                            'Request method' => 'GET',
                            'Event name' => 'process_get',
                            'Controller' => $this->getTestedClass(),
                            'Exception' => $exception
                        ]
                    )
                ]
            );

        $this->getInvocationBuilder($dispatcher, $this->exactly(2), 'dispatch')
            ->withConsecutive(
                [
                    $this->equalTo('process_get'),
                    $this->isInstanceOf(ProcessEvent::class)
                ],
                [
                    $this->equalTo('response_get'),
                    $this->isInstanceOf(ResponseEvent::class)
                ]
            )->willReturnCallback(
                function ($eventName) use ($exception, $responseEvent) {
                    if ($eventName == 'process_get') {
                        throw $exception;
                    }

                    return $responseEvent;
                }
            );

        $this->getInvocationBuilder($responseEvent, $this->once(), 'getResponse')
            ->willReturn($exception);

        $instance = $this->getInstance(
            [
                'logger' => $logger,
                'eventDispatcher' => $dispatcher,
                'processSuffix' => 'process_',
                'responseSuffix' => 'response_'
            ]
        );

        $this->assertSame($exception, $instance->execute($request, $baseEvent));
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
        return ApiController::class;
    }
}
