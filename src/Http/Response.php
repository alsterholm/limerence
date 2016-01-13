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
 * HTTP Response.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Http
 * @since 0.0.1
 */
class Response
{
    /**
     * @var array Response headers
     */
    private $headers;

    /**
     * @var mixed Response body
     */
    private $body;

    /**
     * @var int Response status
     */
    private $status;

    /**
     * @var int Expected response status
     */
    private $expectedStatus;

    /**
     * Creates a new HTTP response object.
     * 
     * @param $headers array
     * @param $body string
     */
    public function __construct($headers, $body, $status)
    {
        global $limerenceTestRunner;

        $this->runner = $limerenceTestRunner;

        $this->headers = $headers;
        $this->parseHeaders();

        $this->body = $body;
        $this->parseBody();

        $this->expectedStatus = $status;
        $this->checkStatus();
    }

    /**
     * Parse the response headers.
     */
    private function parseHeaders()
    {
        $headers = array();

        foreach ($this->headers as $header) {
            if (strpos($header, ': ') !== false) {
                $header = explode(': ', $header);
                $headers[$header[0]] = $header[1];
            } else if (strpos($header, 'HTTP') !== false) {
                $this->status = intval(substr($header, 9, 3));
            }
        }

        $this->headers = $headers;
    }

    /**
     * Parse the response body.
     */
    private function parseBody()
    {
        if (isset($this->headers['Content-Type']) && (strrpos($this->headers['Content-Type'], 'application/json') !== false)) {
            $this->body = json_decode($this->body);
        } else if (isset($this->headers['Content-type']) && (strrpos($this->headers['Content-type'], 'application/json') !== false)) {

        }
    }

    /**
     * Validate the response status against the one
     * expected by the user.
     */
    private function checkStatus()
    {
        if ($this->expectedStatus && $this->status) {
            if ($this->expectedStatus != $this->status) {
                $expected = $this->expectedStatus . " " . Message::$http_status_codes[$this->expectedStatus];
                $real = $this->status . " " . Message::$http_status_codes[$this->status];

                $this->runner->addError("Expected \"$expected\", but got \"$real\".");
            }
        }
    }

    public function __get($var)
    {
        if (property_exists($this, $var)) {
            return $this->{$var};
        }

        return null;
    }

    /**
     * Returns true if an error occured,
     * or false if everything went fine.
     */
    public function getError()
    {
        return $this->headers ? false : true;
    }
}