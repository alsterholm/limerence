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

namespace Limerence\Behaviour;

use Limerence\Assertions\Be;
use Limerence\Assertions\Contain;
use Limerence\Assertions\Equal;
use Limerence\Assertions\Have;

/**
 * Chainer that negates the assertion.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Behaviour
 * @since 0.0.1
 */
class Not
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
     * Not-chainer
     * 
     * @param $runner \Limerence\Test\TestRunner
     * @param $subject mixed Value to assert
     */
    public function __construct($runner, $subject)
    {
        $this->runner = $runner;
        $this->subject = $subject;
    }

    /**
     * Chainer method
     * 
     * @return \Limerence\Assertions\Have
     */
    private function have()
    {
        $have = new Have($this->runner, $this->subject, true);
        return $have;
    }

    /**
     * Chainer method
     * 
     * @return \Limerence\Assertions\Be
     */
    private function be()
    {
        $be = new Be($this->runner, $this->subject, true);
        return $be;
    }

    /**
     * Chainer method
     * 
     * @return \Limerence\Assertions\Contain
     */
    public function contain($expectation)
    {
        $contain = new Contain($this->runner, $this->subject, $expectation, true);
        return $contain;
    }

    /**
     * Chainer method
     * 
     * @param $expectation mixed Expected value
     * 
     * @return \Limerence\Assertions\Equal
     */
    public function equal($expectation)
    {
        $equal = new Equal($this->runner, $this->subject, $expectation, true);
        return $equal;
    }

    public function __get($var)
    {
        if (!method_exists($this, $var)) {
            throw new Exception();
        }

        if ($var == 'contain') {
            $contain = new Contain($this->runner, $this->subject, null, true);
        }

        return $this->{$var}();
    }
}