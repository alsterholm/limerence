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
 * HTTP Message.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Http
 * @since 0.0.1
 */
class Message
{
    /**
     * @var string URL
     */
    private $url;

    /**
     * @var array Options
     */
    private $options = array();

    public static $http_status_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
    );

    /**
     * Creates a new HTTP message.
     * 
     * @param $url      string  Target URL
     * @param $method   string  HTTP method
     * @param $headers  array   Headers to send
     * @param $data     array   Data to send
     */
    public function __construct($url, $method, $headers, $data)
    {
        $this->url = $url;

        $options = array(
            'http' => array(
                'header'    => $this->headers($headers),
                'method'    => $method,
                'content'   => http_build_query($data),
            ),
        );
    }

    /**
     * Send the HTTP message.
     * 
     * @param $status int
     * @return array
     */
    public function send($status)
    {
        $context = stream_context_create($this->options);
        $body = @file_get_contents($this->url, false, $context);

        $http_response_header = isset($http_response_header) ? $http_response_header : array();

        $response = new Response($http_response_header, $body, $status);
        return array($response->getError(), $response);
    }

    /**
     * Format headers properly.
     * 
     * @param $headers
     */
    private function headers($headers)
    {
        $h = array();

        foreach ($headers as $header => $value) {
            $h[] = "$header: $value";
        } 

        return $h;
    }
}