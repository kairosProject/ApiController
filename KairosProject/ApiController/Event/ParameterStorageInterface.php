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
 * Parameter storage interface
 *
 * This interface define the main methods used by the class with internal parameter storage.
 *
 * @category Api_Controller_Event
 * @package  Kairos_Project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
interface ParameterStorageInterface
{
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
