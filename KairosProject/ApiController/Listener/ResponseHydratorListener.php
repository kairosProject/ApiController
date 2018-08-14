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
 * @category Api_Controller_Listener
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Listener;

use KairosProject\ApiController\Event\ProcessEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Response hydrator listener
 *
 * This class represent the default implementation of the ResponseHydratorListenerInterface. It responsible of the
 * parameters assignment as response.
 *
 * @category Api_Controller_Listener
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ResponseHydratorListener implements ResponseHydratorListenerInterface
{
    /**
     * Parameter name
     *
     * The parameter name, to be used as event parameter name, allowing to get the response content
     *
     * @var string
     */
    private $parameterName;

    /**
     * Construct
     *
     * The default ResponseHydratorListener constructor. Will store the given parameter name to use it at the hydration
     * process as parameter name.
     *
     * @param string $parameterName The name of the parameter whence get the response
     *
     * @return void
     */
    public function __construct(string $parameterName)
    {
        $this->parameterName = $parameterName;
    }

    /**
     * Hydrate response
     *
     * This method, accordingly with the configuration will insert an event parameter into the response of this same
     * event.
     *
     * @param ProcessEventInterface    $event      The original event, where is located the parameter
     * @param string                   $eventName  The leading event name
     * @param EventDispatcherInterface $dispatcher The original event dispatcher
     *
     * @return void
     */
    public function hydrateResponse(ProcessEventInterface $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if ($event->hasParameter($this->parameterName)) {
            $event->setResponse($event->getParameter($this->parameterName));
            return;
        }

        $format = 'The specified parameter "%s" does not exist in the event parameter bag.';
        $format .= 'This can be due to an unplugged listener or even an miscatched exception.';

        throw new \LogicException(sprintf($format, $this->parameterName));
    }
}
