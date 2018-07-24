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
 * @category Api_Controller_Event
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\ApiController\Event;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Process event interface
 *
 * This interface describe the API event in cahrge of the shipping of the request between each process listeners.
 *
 * @category Api_Controller_Event
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ProcessEventInterface extends ParameterStorageInterface
{
    /**
     * Get the request.
     *
     * This method will return a request instance, initialy injected by inversion of control.
     *
     * @return ServerRequestInterface
     */
    public function getRequest() : ServerRequestInterface;

    /**
     * Set process response.
     *
     * This method will store a data in order to be used by the response formating event.
     *
     * @param mixed $responseData The process logic resut
     *
     * @return ProcessEventInterface
     */
    public function setResponse($responseData) : ProcessEventInterface;

    /**
     * Get the process response.
     *
     * This method return the process logic result.
     *
     * @return mixed
     */
    public function getResponse();
}
