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

use Limerence\Behaviour\Expect;
use Limerence\Http\Request;
use Limerence\Utilities\CLI;

/**
 * Defines a new test suite.
 * 
 * @param $description  string      Description of what module is being tested
 * @param $callback     Callable    Test suite code
 */
function test($description, $callback)
{
    global $limerenceTestRunner;

    $limerenceTestRunner->addTestSuite($description, $callback);
}

/**
 * Defines a test case.
 * 
 * @param $description  string      Description of test case
 * @param $callback     Callable    Described block of code
 */
function describe($description, $callback)
{
    global $limerenceTestRunner;

    $limerenceTestRunner->addTestCase($description, $callback);
}

/**
 * Defines an assertion.
 * 
 * @param $should   string      Description of what the tested feature should accomplish
 * @param $callback Callable    Code block containing all test code.
 */
function it($should, $callback)
{
    global $limerenceTestRunner;

    $testCase = $limerenceTestRunner->getCurrentTestcase();
    $testCase->defineAssertion($should, $callback);
}

/**
 * Creates a new expectation.
 * 
 * @param $subject mixed
 * @return \Limerence\Behaviour\Expect
 */
function expect($subject)
{
    $expect = new Expect($subject);
    return $expect;
}

/**
 * Creates a new HTTP request.
 * 
 * @param $method   string
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function request($method, $endpoint = null)
{
    $request = new Request($method, $endpoint);
    return $request;
}

/**
 * Alias for request('GET', $endpoint)
 * 
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function get($endpoint)
{
    return request('GET', $endpoint);
}

/**
 * Alias for request('POST', $endpoint)
 * 
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function post($endpoint)
{
    return request('POST', $endpoint);
}

/**
 * Alias for request('PUT', $endpoint)
 * 
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function put($endpoint)
{
    return request('PUT', $endpoint);
}

/**
 * Alias for request('PATCH', $endpoint)
 * 
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function patch($endpoint)
{
    return request('PATCH', $endpoint);
}

/**
 * Alias for request('DELETE', $endpoint)
 * 
 * @param $endpoint string
 * @return \Limerence\Http\Request
 */
function delete($endpoint)
{
    return request('DELETE', $endpoint);
}