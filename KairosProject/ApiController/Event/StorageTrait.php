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

/**
 * Storage trait
 *
 * This trait provide the shared methods used by the storage interface implementation.
 *
 * @category Api_Controller_Event
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
trait StorageTrait
{
    /**
     * Storage.
     *
     * An ArrayObject instance, to store the event parameters.
     *
     * @var \ArrayObject
     */
    private $storage;

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
    public function setParameter(string $parameterName, $parameterValue)
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
    public function setParameters(array $parameters)
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
