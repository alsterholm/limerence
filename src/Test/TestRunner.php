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

use Limerence\Utilities\CLI;

/**
 * Test runner.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Test
 * @since 0.0.1
 */
class TestRunner
{
    /**
     * @var string Limerence version
     */
    private $version = "0.0.1";

    /**
     * @var array Test suites
     */
    private $tests = array();

    /**
     * @var \Limerence\Test\TestCase Current running test case
     */
    private $test;

    /**
     * @var int Number of passing tests
     */
    private $passing = 0;

    /**
     * @var int Number of failing tests
     */
    private $failing = 0;

    /**
     * @var int Number of assertions
     */
    private $assertions = 0;

    /**
     * @var array
     */
    private $errors = array();

    public function addTestSuite($description, $callback)
    {
        $this->testSuite = new TestSuite($description, $callback);
        $this->testSuites[] = $this->testSuite;
    }

    /**
     * Add a new test case to the runner.
     * 
     * @param $filename string Path to test file
     */
    public function addTestCase($description, $callback)
    {
        $testCase = new TestCase($description, $callback);
        $this->testSuite->addTestCase($testCase);

        return $testCase;
    }

    /**
     * Run all tests.
     */
    public function run()
    {
        echo CLI::color("blue", "\nLimerence ");
        echo CLI::color("grey", "v{$this->version}\n");
        echo "\nTests:\n‾‾‾‾‾‾";

        $time = microtime();

        foreach ($this->testSuites as $testSuite) {
            $this->testSuite = $testSuite;
            $this->testSuite->gatherTestCases();
        }

        foreach ($this->testSuites as $testSuite) {
            $this->testSuite = $testSuite;
            $this->testSuite->run();
        }

        if ($this->errors) {
            echo CLI::color("red", "\n\nErrors:\n‾‾‾‾‾‾‾\n");
            foreach ($this->errors as $error) {
                echo "$error\n\n";
            }
        }

        $this->time = microtime() - $time;

        $numberOfTests = $this->passing + $this->failing;

        echo "\n\n{$numberOfTests} tests ({$this->assertions} assertions) completed in " . number_format($this->time, 2) . " ms.\n\n";
        echo CLI::color("green", "{$this->passing} tests passing.\n");
        echo CLI::color("red", "{$this->failing} tests failing.\n\n");
    }

    /**
     * Returns the current running test case.
     * 
     * @return \Limerence\Test\TestCase
     */
    public function getCurrentTestCase()
    {
        return $this->testSuite->getCurrentTestCase();
    }

    public function increment($success)
    {
        if ($success) {
            $this->passing++;
        } else {
            $this->failing++;
        }
    }

    public function incrementAssertions()
    {
        $this->assertions++;
    }

    public function addError($message)
    {
        $description = $this->testSuite->getFullDescription();
        $error = new Error($description, $message);
        $this->errors[] = $error;
    }

    public function markCurrentAsFailed()
    {
        $testCase = $this->getCurrentTestCase();
        $testCase->failed();
    }
}