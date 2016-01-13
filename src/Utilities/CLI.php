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

namespace Limerence\Utilities;

/**
 * Command line utilities.
 * 
 * @author Andreas Indal <andreas@rocketship.se>
 * @package Limerence\Utilities
 * @since 0.0.1
 */
class CLI
{
    /**
     * Color the CLI output
     * 
     * @param $color int Color value
     * @param $string string String to add color to
     * @return string
     */
    public static function color($color, $string)
    {
        $colors = array(
            "blue"      => 39,
            "green"     => 77,
            "grey"      => 243,
            "red"       => 167,
            "yellow"    => 227,
        );

        if (isset($colors[$color])) {
            $color = $colors[$color];
            $string = "\e[38;5;{$color}m$string\e[m";
        }

        return $string;
    }

    /**
     * Make any value stringified.
     * 
     * @param $var Value to stringify
     * @return string
     */
    public static function toString($var)
    {
        switch (true) {
            case is_array($var):
                foreach ($var as $key => $value) {
                    $var[$key] = self::toString($value);
                }
                return '[ ' . implode(', ', $var) . ' ]';

            case is_object($var):
                return get_class($var) . '-object';

            case is_callable($var):
                return 'Callable function';

            case is_numeric($var):
                return $var;

            case is_string($var):
                return "'$var'";

            case is_null($var):
                return "NULL";

            default:
                return $var;
        }
    }
}