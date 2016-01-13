<?php
/**
 * Limerence
 * 
 * BDD/TDD testing library for PHP.
 * 
 * Copyright (c) 2016 Andreas Indal
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * @author      Andreas Indal <andreas@rocketship.se>
 * @copyright   Copyright (c) 2016 Andreas Indal
 * @link        https://github.com/andreasindal/limerence
 * @version     0.0.1
 * @license     MIT
 */

namespace Limerence\Http;

/**
 * HTTP Request.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Http
 * @since 0.0.1
 */
class Request
{
    /**
     * @var int Expected response status
     */
    private $expectedStatus;

    /**
     * @var array Valid request methods
     */
    private $requestMethods = array(
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
    );

    /**
     * @var string Target hostname
     * 
     * Default is localhost
     */
    private $hostname;

    /**
     * @var string Target endpoint
     */
    private $endpoint;

    /**
     * @var int Target port
     * 
     * Default is 80
     */
    private $port;

    /**
     * @var string HTTP method to use
     * 
     * Default is GET
     */
    private $method;

    /**
     * @var array Data to send
     */
    private $data = array();

    /**
     * @var array Headers to send
     */
    private $headers = array();

    /**
     * Constructs a new HTTP Request object.
     * 
     * @param $method string Method to use
     * @param $endpoint
     */
    public function __construct($method, $endpoint = null)
    {
        $this->hostname = 'localhost';
        $this->port = 80;
        $this->protocol = 'http';

        $method = strtoupper($method);

        # Check if first argument is a valid method or not,
        # otherwise expects it to be the endpoint.
        if (!in_array($method, $this->requestMethods)) {
            $this->method = 'GET';
            $this->endpoint = trim($method, '/');
        } else {
            $this->method = $method;
            $this->endpoint = trim($endpoint, '/');
        }
    }

    /**
     * Sets the hostname to use. Defaults to localhost.
     * 
     * @param $host string
     * @return \Limerence\Http\Request
     */
    public function at($hostname = 'localhost')
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Sets the port to use. Defaults to 80.
     * 
     * @param $port int Port
     * @return \Limerence\Http\Request
     */
    public function on($port = 80)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Sets the protocol to use. Defaults to HTTP.
     * 
     * @param $protocol string
     * @return \Limerence\Http\Request
     */
    public function protocol($protocol = 'http')
    {
        if ($protocol == 'http' || $protocol == 'https') {
            $this->protocol = $protocol;
        }

        return $this;
    }

    /**
     * Adds a header to the request.
     * 
     * @param $header string Type
     * @param $value  string Value
     */
    public function with($header, $value)
    {
        if (is_array($header)) {
            list($header, $value) = $header;
        }

        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Sets the data to send.
     * 
     * @param $data array
     * @param $json boolean
     */
    public function send($data = [], $json = false)
    {
        if ($json) {
            $this->data = json_encode($data);
            $this->headers['Content-type'] = 'application/json';
        } else {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * Set the status to expect the response
     * to have.
     * 
     * @param $status int Expected status code
     */
    public function expect($status)
    {
        $this->expectedStatus = $status;
        return $this;
    }

    /**
     * Send the HTTP request away.
     * 
     * @param $callback Callable
     */
    public function end($callback)
    {
        $message = new Message(
            $this->url(),
            $this->method,
            $this->headers,
            $this->data
        );

        list($error, $response) = $message->send($this->expectedStatus);

        $callback($error, $response);
    }

    /**
     * Creates the url to use.
     */
    private function url()
    {
        $url = "{$this->protocol}://{$this->hostname}:{$this->port}/{$this->endpoint}";

        return $url;
    }
}