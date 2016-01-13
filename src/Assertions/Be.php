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

use Limerence\Exceptions\UnknownTypeException;
use Limerence\Utilities\CLI;

/**
 * Assertion that validates a values type.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Assertions
 * @since 0.0.1
 */
class Be
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
     * Create a new be-assertion.
     * 
     * @param $runner   \Limerence\Test\TestRunner
     * @param $subject  mixed   Value to assert
     * @param $negate   boolean Whether to negate the assertion or not    
     */
    public function __construct($runner, $subject, $negate = false)
    {
        $this->runner = $runner;
        $this->subject = $subject;
        $this->negate = $negate;
    }


    /**
     * Validate the assertion of a value’s type.
     * 
     * @param $expectation mixed
     * @param $target object Optional target object
     */
    public function a($expectation)
    {
        switch ($expectation) {
            case 'string':
                $this->assertString();
                break;

            case 'callable':
                $this->assertCallable();
                break;

            case 'bool':
            case 'boolean':
                $this->assertBoolean();
                break;

            case 'number':
            case 'numeric':
                $this->assertNumeric();
                break;

            case 'json':
                $this->assertJSON();
                break;

            case 'file':
                $this->assertFile();
                break;

            case 'null':
                $this->assertNull();
                break;

            case 'empty':
                $this->assertEmpty();
                break;

            case 'writable':
                $this->assertWritable();
                break;

            case 'int':
            case 'long':
            case 'integer':
                $this->assertInteger();
                break;

            case 'float':
            case 'double':
            case 'real':
                $this->assertFloat();
                break;

            case 'object':
                $this->assertObject();
                break;

            case 'array':
                $this->assertArray();
                break;

            default:
                throw new UnknownTypeException("Unknown type: \"$expectation\"");
        }
    }

    /**
     * Alias for the a() method.
     * 
     * @param $expectation mixed
     */
    public function an($expectation)
    {
        $this->a($expectation);
    }

    /**
     * Assert a value to be a string.
     */
    private function assertString()
    {
        $expectation = is_string($this->subject);
        $this->assert($expectation, "string", gettype($this->subject));
    }

    /**
     * Assert a value to be a numeric value.
     */
    private function assertNumeric()
    {
        $expectation = is_numeric($this->subject);
        $this->assert($expectation, "numeric value", gettype($this->subject));
    }

    /**
     * Assert a value to be an integer.
     */
    private function assertInteger()
    {
        $expectation = is_int($this->subject);
        $this->assert($expectation, "integer", gettype($this->subject));
    }

    /**
     * Assert a value to be a floating point number.
     */
    private function assertFloat()
    {
        $expectation = is_float($this->subject);
        $this->assert($expectation, "float", gettype($this->subject));
    }

    /**
     * Assert a value to be a boolean.
     */
    private function assertBoolean()
    {
        $expectation = is_bool($this->subject);
        $this->assert($expectation, "boolean", gettype($this->subject));
    }

    /**
     * Assert a value to be an object.
     */
    private function assertObject()
    {
        $expectation = is_object($this->subject);
        $this->assert($expectation, "object", gettype($this->subject));
    }

    /**
     * Assert a value to be an array.
     */
    private function assertArray()
    {
        $expectation = is_array($this->subject);
        $this->assert($expectation, "array", gettype($this->subject));
    }

    /**
     * Assert a value to be a file.
     */
    private function assertFile()
    {
        $expectation = is_file($this->subject);
        $this->assert($expectation, "file", gettype($this->subject));
    }

    /**
     * Assert a value to be a callable.
     */
    private function assertCallable()
    {
        $expectation = is_callable($this->subject);
        $this->assert($expectation, "callable", gettype($this->subject));
    }

    /**
     * Assert a value to be empty.
     */
    private function assertEmpty()
    {
        $expectation = empty($this->subject);
        $this->assert($expectation, "empty");
    }

    /**
     * Assert a value to be null.
     */
    private function assertNull()
    {
        $expectation = is_null($this->subject);
        $this->assert($expectation, "null", gettype($this->subject));
    }

    /**
     * Assert a value to be writable.
     */
    private function assertWritable()
    {
        $expectation = is_writeable($this->subject);

        $this->assert($expectation, "writable");
    }

    /**
     * Assert a value to be a JSON string.
     */
    private function assertJSON()
    {
        if (is_numeric($this->subject)) {
            # Numbers are considered to be valid json according to
            # json_last_error(), so we need to check for that manually.
            $expectation = false;
        } else if (isset($this->subject[0]) && !in_array($this->subject, array('[', '{'))) {
            # Check the first character to prevent unneccessary
            # decoding.
            $expectation = false;
        } else {
            json_decode($this->subject);
            $expectation = json_last_error() == JSON_ERROR_NONE;
        }

        $this->assert($expectation, "json");
    }

    /**
     * Make the assertion and report result to the test runner.
     * 
     * @param $expectation  boolean     Success
     * @param $type         string      Subject type
     * @param $value        string      Expected type
     */
    private function assert($expectation, $type, $value = "")
    {
        $subject = CLI::toString($this->subject);

        if ($this->negate) {
            $expectation = !$expectation;
            $not = " not ";
        } else {
            $not = " ";
        }

        if (!$expectation) {
            $message = "Expected $subject to{$not}be {$type}";

            if ($value && !$this->negate) {
                $message .= ", but is a $value.";
            } else {
                $message .= ".";
            }

            $this->runner->addError($message);
            $this->runner->markCurrentAsFailed();
        }
    }
}