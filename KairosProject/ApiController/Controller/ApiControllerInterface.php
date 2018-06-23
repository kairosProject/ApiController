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
 * @category Api controller
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Api controller interface
 *
 * This interface describe the API default controller
 *
 * @category Api controller
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ApiControllerInterface
{
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
     * @param ServerRequestInterface $request       The request to be handled by the execution
     * @param string                 $eventBaseName The base event name to be processed
     *
     * @return mixed
     */
    public function execute(ServerRequestInterface $request, string $eventBaseName);
}

