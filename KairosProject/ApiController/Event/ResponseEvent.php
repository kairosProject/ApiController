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

use Symfony\Component\EventDispatcher\Event;

/**
 * Response event interface
 *
 * This API event in charge of the shipping of the process result during the response formatting process.
 *
 * @category Api controller event
 * @package  Kairos project
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ResponseEvent extends Event implements ResponseEventInterface
{
    /**
     * Response.
     *
     * Store the response data in order to be retreived during response formating.
     *
     * @var mixed
     */
    private $response;

    /**
     * Get response
     *
     * Return the stored response to be used by listeners or further filter to be converted into http response.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response data.
     *
     * This method store the response data, in any format to be used by listeners or further filter to be converted
     * into http response.
     *
     * @param mixed $responseData The data to store
     *
     * @return $this
     */
    public function setResponse($responseData)
    {
        $this->response = $responseData;
        return $this;
    }
}
