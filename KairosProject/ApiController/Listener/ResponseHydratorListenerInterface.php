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
 * Response hydrator listener interface
 *
 * This interface define the methods of the response hydrator. Such hydrator are responsible for the assignment of a
 * parameter as response
 *
 * @category Api_Controller_Listener
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ResponseHydratorListenerInterface
{
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
    public function hydrateResponse(
        ProcessEventInterface $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    );
}
