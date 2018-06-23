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
 * @category Api controller trait
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Traits;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use KairosProject\ApiController\Event\ProcessEvent;
use KairosProject\ApiController\Event\ResponseEvent;

/**
 * Execution trait
 *
 * This trait implements the logic of the ApiController. It allows the end users to reimplement the logic into their
 * own controller with the ability to extends a framework specific controller.
 *
 * @category Api controller trait
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait ExecutionTrait
{
    /**
     * Logger instance
     *
     * The logger instance embedded into the executor instance is used to ensure investigation facility and security.
     *
     * The part of investigation facility is a best practice commonly agreed by the development community, that allows
     * receiving information about the internal software process.
     *
     * About the security part, the OWASP top ten threat has to be taken into consideration.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Event dispatching instance
     *
     * The API controller system is designed to be easily extendable and will implement an event dispatching system,
     * allowing the attachment and separation of logic by priority.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Event process suffix
     *
     * This prefix is used to allow interoperability with end user specific needs in term of event naming. This string
     * sentence will be automatically added to the base event name.
     *
     * @var string
     */
    private $processSuffix;

    /**
     * Event response suffix
     *
     * This prefix is used to allow interoperability with end user specific needs in term of event naming. This string
     * sentence will be automatically added to the base event name.
     *
     * @var string
     */
    private $responseSuffix;

    /**
     * Execution trait constructor
     *
     * The default constructor for the current trait implementation.
     *
     * It will register the logger instance, the event dispatcher instance and the two optional suffix for the
     * implementation class.
     *
     * @param LoggerInterface $logger                   The logger, to be stored
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher, to be stored
     * @param string $processSuffix                     The event process suffix, to be stored
     * @param string $responseSuffix                    The event response suffix, to be stored
     *
     * @return void
     */
    public function __construct(
        LoggerInterface $logger,
        EventDispatcherInterface $eventDispatcher,
        string $processSuffix = 'process_',
        string $responseSuffix = 'response_'
    ) {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->processSuffix = $processSuffix;
        $this->responseSuffix = $responseSuffix;
    }

    /**
     * Execute a request process.
     *
     * The execute method is designed to dispatch the request between a list of processing listeners. These listeners
     * will provide a sequential logic to result in a response element. The response element itself is not expected
     * to be on a specific form and by the way can be anything.
     *
     * In cooperation with the processing listener set, a list of response listeners will be applied to format the
     * processing result into a response or any element that will be converted into a response by a framework filter.
     *
     * Fail-safe state of the processing part is guaranteed. In case of an exception throwing, the given exception
     * will be provided to the response formatting listeners.
     *
     * Regarding the event name itself, the processing and response steps will add a prefix to the base event name.
     * These prefixes are specified by the instance constructor.
     *
     * @param ServerRequestInterface $request       The request to be handled by the execution and passed through
     *                                              process listeners
     * @param string                 $eventBaseName The base event name to be processed
     *
     * @return mixed
     */
    public function execute(ServerRequestInterface $request, string $eventBaseName)
    {
        $processEventName = sprintf('%s%s', $this->processSuffix, $eventBaseName);

        $this->logger->debug(
            'Processing started',
            [
                'Request method' => $request->getMethod(),
                'Event name' => $processEventName,
                'Controller' => static::class
            ]
        );

        $processEvent = new ProcessEvent($request);

        try {
            $this->eventDispatcher->dispatch($processEventName, $processEvent);
        } catch (\Exception $processException) {
            $this->logger->error(
                'Process error',
                [
                    'Request method' => $request->getMethod(),
                    'Event name' => $processEventName,
                    'Controller' => static::class,
                    'Exception' => $processException
                ]
            );

            $processEvent->setResponse($processException);
        }

        $responseEvent = new ResponseEvent();
        $responseEvent->setResponse($processEvent->getResponse());

        $responseEventName = sprintf('%s%s', $this->responseSuffix, $eventBaseName);
        $this->logger->debug(
            'Rendering started',
            [
                'Event name' => $responseEventName,
            ]
        );

        $this->eventDispatcher->dispatch($responseEventName, $responseEvent);
        return $responseEvent->getResponse();
    }
}

