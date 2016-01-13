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
 * Assertion that validates a value against another.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Assertions
 * @since 0.0.1
 */
class Equal
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
     * @var mixed Expected value
     */
    protected $expectation;

    /**
     * @var boolean Whether to negate the assertion or not
     */
    protected $negate;

    /**
     * Create a new equal-assertion.
     * 
     * @param $runner       \Limerence\Test\TestRunner
     * @param $subject      mixed       Value to assert
     * @param $expectation  mixed       Expected value
     * @param $negate       boolean     Whether to negate the assertion or not
     */
    public function __construct($runner, $subject, $expectation, $negate = false)
    {
        $this->runner = $runner;
        $this->subject = $subject;
        $this->expectation = $expectation;
        $this->negate = $negate;
        $this->assert();
    }

    /**
     * Make the assertion and report result to the test runner.
     */
    private function assert()
    {
        if ($this->negate) {
            $expectation = $this->subject != $this->expectation;
            $not = " not ";
        } else {
            $expectation = $this->subject == $this->expectation;
            $not = " ";
        }

        $subject = CLI::toString($this->subject);
        $value = CLI::toString($this->expectation);

        if (!$expectation) {
            $this->runner->addError("Expected $subject to{$not}equal $value");
            $this->runner->markCurrentAsFailed();
        }
    }
}