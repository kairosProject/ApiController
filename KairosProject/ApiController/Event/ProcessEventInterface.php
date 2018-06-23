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
 * @category Api controller event
 * @package  Kairos project
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
 * @category Api controller event
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ProcessEventInterface
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

    /**
     * Set a parameter.
     *
     * Store a parameter value, later accessible by the given parameter key. The value is overwrited if already exist.
     *
     * @param string  $parameterName  The parameter key to get back the value
     * @param mixed   $parameterValue The parameter value to be stored
     *
     * @return $this
     */
    public function setParameter(string $parameterName, $parameterValue) : ProcessEventInterface;

    /**
     * Get a parameter.
     *
     * Return a parameter value by it's name. Will return null if not already stored.
     *
     * @param string $parameterName The parameter key
     *
     * @return mixed
     */
    public function getParameter(string $parameterName);

    /**
     * Parameter exist.
     *
     * Return the current existance state of a stored parameter.
     *
     * @param string $parameterName The parameter key
     *
     * @return bool
     */
    public function hasParameter(string $parameterName) : bool;

    /**
     * Set parameters.
     *
     * Overwrite the current parameter storage with the given one.
     *
     * @param array $parameters The list of new parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters) : ProcessEventInterface;

    /**
     * Get parameters.
     *
     * Return the complete list of stored parameters into an array, with parameter names as key.
     *
     * @return array
     */
    public function getParameters() : array;
}

