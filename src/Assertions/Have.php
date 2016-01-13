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

namespace Limerence\Assertions;

use Limerence\Utilities\CLI;

/**
 * Assertion that validates a have-condition.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Assertions
 * @since 0.0.1
 */
class Have
{
    /**
     * @var \Limerence\Test\TestRunner
     */
    protected $runner;

    /**
     * @var mixed Value to assert
     */
    protected $subject;

    /**
     * @var boolean Whether to negate the assertion or not
     */
    protected $negate;

    /**
     * Create a new have-assertion.
     * 
     * @param $runner       \Limerence\Test\TestRunner
     * @param $subject      mixed       Value to assert
     * @param $negate       boolean     Whether to negate the assertion or not
     */
    public function __construct($runner, $subject, $negate = false)
    {
        $this->runner = $runner;
        $this->subject = $subject;
        $this->negate = $negate;
    }

    /**
     * Validate the existance of a property.
     * 
     * @param $property string Property name
     */
    public function property($property)
    {
        $expectation = @property_exists($this->subject, $property);
        $this->assert($expectation, "property", $property);
    }

    /**
     * Validate the existance of a method.
     * 
     * @param $method string Method name
     */
    public function method($method)
    {
        $expectation = @method_exists($this->subject, $method);
        $this->assert($expectation, "method", $method);
    }

    /**
     * Make the assertion and report result to the test runner.
     * 
     * @param $type         string  Either property or method
     * @param $name         string  Name of the property/method
     * @param $expectation  boolean
     */
    private function assert($expectation, $type, $value)
    {
        if ($this->negate) {
            $expectation = !$expectation;
            $not = " not ";
        } else {
            $not = " ";
        }

        $subject = CLI::toString($this->subject);

        if (!$expectation) {
            $this->runner->addError("Expected $subject to{$not}have $type $value.");
            $this->runner->markCurrentAsFailed();
        }
    }
}