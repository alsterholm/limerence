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

namespace Limerence\Test;

/**
 * Test suite.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Test
 * @since 0.0.1
 */
class TestSuite
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $testCases = array();

    /**
     * @var \Limerence\Test\TestCase
     */
    private $currentTestCase;

    /**
     * @var Callable
     */
    private $callback;

    /**
     * Create a new test suite.
     * 
     * @param $description  string
     * @param $callback     Callable
     */
    public function __construct($description, $callback)
    {
        $this->description = $description;
        $this->callback = $callback;
    }

    /**
     * Add a test case to the suite.
     * 
     * @param $testCase \Limerence\Test\TestCase
     */
    public function addTestCase($testCase)
    {
        $this->testCases[] = $testCase;
        $this->currentTestCase = $testCase;
    }

    /**
     * Set up all test cases.
     */
    public function gatherTestCases()
    {
        call_user_func($this->callback);
    }

    /**
     * Run all tests.
     */
    public function run()
    {
        echo "\n  {$this->description}\n";
        foreach ($this->testCases as $testCase) {
            $this->currentTestCase = $testCase;
            $this->currentTestCase->run();
        }
    }

    /**
     * Get number of test cases in suite.
     * 
     * @return int
     */
    public function countTestCases()
    {
        return count($this->testCases);
    }

    /**
     * Get the currently running test case.
     * 
     * @return \Limerence\Test\TestCase
     */
    public function getCurrentTestCase()
    {
        return $this->currentTestCase;
    }

    /**
     * Get the full description of the suite + test case.
     * 
     * @return string
     */
    public function getFullDescription()
    {
        $testCaseDescription = $this->currentTestCase->getFullDescription();
        return "$this->description -> $testCaseDescription";
    }
}