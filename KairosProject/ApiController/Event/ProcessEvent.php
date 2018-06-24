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

use Symfony\Component\EventDispatcher\Event;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Process event
 *
 * This API event is in cahrge of the shipping of the request between each process listeners.
 *
 * @category Api_Controller_Event
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ProcessEvent extends Event implements ProcessEventInterface
{
    /**
     * Request.
     *
     * This property store the initial process request to be used during the processing by the listener set.
     *
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * Response.
     *
     * This property store the process result.
     *
     * @var mixed
     */
    private $response;

    /**
     * Storage.
     *
     * An ArrayObject instance, to store the event parameters.
     *
     * @var \ArrayObject
     */
    private $storage;

    /**
     * Process event constructor.
     *
     * This default ProcessEventConstructor will store a request instance, in order to be accessed by the process
     * listeners.
     *
     * In addition, a new ArrayObject instance will be stored as internal event storage.
     *
     * @param ServerRequestInterface $request The original request instance
     *
     * @return void
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->storage = new \ArrayObject();
    }

    /**
     * Get the request.
     *
     * This method will return a request instance, initialy injected by inversion of control.
     *
     * @return ServerRequestInterface
     */
    public function getRequest() : ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Set process response.
     *
     * This method will store a data in order to be used by the response formating event.
     *
     * @param mixed $responseData The process logic resut
     *
     * @return ProcessEventInterface
     */
    public function setResponse($responseData) : ProcessEventInterface
    {
        $this->response = $responseData;
        return $this;
    }

    /**
     * Get the process response.
     *
     * This method return the process logic result.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set a parameter.
     *
     * Store a parameter value, later accessible by the given parameter key. The value is overwrited if already exist.
     *
     * @param string $parameterName  The parameter key to get back the value
     * @param mixed  $parameterValue The parameter value to be stored
     *
     * @return $this
     */
    public function setParameter(string $parameterName, $parameterValue) : ProcessEventInterface
    {
        $this->storage->offsetSet($parameterName, $parameterValue);
        return $this;
    }

    /**
     * Get a parameter.
     *
     * Return a parameter value by it's name. Will return null if not already stored.
     *
     * @param string $parameterName The parameter key
     *
     * @return mixed
     */
    public function getParameter(string $parameterName)
    {
        if (!$this->hasParameter($parameterName)) {
            return null;
        }

        return $this->storage->offsetGet($parameterName);
    }

    /**
     * Parameter exist.
     *
     * Return the current existance state of a stored parameter.
     *
     * @param string $parameterName The parameter key
     *
     * @return bool
     */
    public function hasParameter(string $parameterName) : bool
    {
        return $this->storage->offsetExists($parameterName);
    }

    /**
     * Set parameters.
     *
     * Overwrite the current parameter storage with the given one.
     *
     * @param array $parameters The list of new parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters) : ProcessEventInterface
    {
        $this->storage = new \ArrayObject();
        foreach ($parameters as $parameterName => $parameterValue) {
            $this->setParameter($parameterName, $parameterValue);
        }

        return $this;
    }

    /**
     * Get parameters.
     *
     * Return the complete list of stored parameters into an array, with parameter names as key.
     *
     * @return array
     */
    public function getParameters() : array
    {
        return iterator_to_array(
            $this->storage->getIterator(),
            true
        );
    }
}
