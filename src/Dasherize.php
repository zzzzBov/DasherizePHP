<?php

/**
 * DasherizePHP - A simple text-to-slug conversion tool
 * <https://github.com/zzzzBov/DasherizePHP>
 * 
 * The MIT License (MIT)
 * 
 * Copyright (c) 2015 zzzzBov
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
 */

namespace zzzzbov\Utils;

class Dasherize {
    const ENCODING = 'encoding';

    private static $charMap = array(
        'Þ' => "th",    // 0x00DE
        'ß' => "ss",    // 0x00DF
        'à' => "a",     // 0x00E0
        'á' => "a",     // 0x00E1
        'â' => "a",     // 0x00E2
        'ã' => "a",     // 0x00E3
        'ä' => "a",     // 0x00E4
        'å' => "a",     // 0x00E5
        'æ' => "ae",    // 0x00E6
        'ç' => "c",     // 0x00E7
        'è' => "e",     // 0x00E8
        'é' => "e",     // 0x00E9
        'ê' => "e",     // 0x00EA
        'ë' => "e",     // 0x00EB
        'ì' => "i",     // 0x00EC
        'í' => "i",     // 0x00ED
        'î' => "i",     // 0x00EE
        'ï' => "i",     // 0x00EF
        'ð' => "o",     // 0x00F0
        'ñ' => "n",     // 0x00F1
        'ò' => "o",     // 0x00F2
        'ó' => "o",     // 0x00F3
        'ô' => "o",     // 0x00F4
        'õ' => "o",     // 0x00F5
        'ö' => "o",     // 0x00F6
        'ø' => "o",     // 0x00F8
        'ù' => "u",     // 0x00F9
        'ú' => "u",     // 0x00FA
        'û' => "u",     // 0x00FB
        'ü' => "u",     // 0x00FC
        'ý' => "y",     // 0x00FD
        'ÿ' => "y",     // 0x00FF
        'ą' => "a",     // 0x0105
        'ć' => "c",     // 0x0107
        'ĉ' => "c",     // 0x0109
        'č' => "c",     // 0x010D
        'đ' => "d",     // 0x0111
        'ę' => "e",     // 0x0119
        'ĝ' => "g",     // 0x011D
        'ğ' => "g",     // 0x011F
        'ĥ' => "h",     // 0x0125
        'ı' => "i",     // 0x0131
        'ĵ' => "j",     // 0x0135
        'ł' => "l",     // 0x0142
        'ń' => "n",     // 0x0144
        'ő' => "o",     // 0x0151
        'œ' => "oe",    // 0x0153
        'ř' => "r",     // 0x0159
        'ś' => "s",     // 0x015B
        'ŝ' => "s",     // 0x015D
        'ş' => "s",     // 0x015F
        'š' => "s",     // 0x0161
        'ŭ' => "u",     // 0x016D
        'ů' => "u",     // 0x016F
        'ŵ' => 'w',     // 0x0175
        'ŷ' => 'y',     // 0x0177
        'ź' => "z",     // 0x017A
        'ż' => "z",     // 0x017C
        'ž' => "z",     // 0x017E
        'ḓ' => 'd',     // 0x1E13
        'ḙ' => 'e',     // 0x1E19
        'ḽ' => 'l',     // 0x1E3D
        'ṋ' => 'n',     // 0x1E4B
        'ṱ' => 't',     // 0x1E71
        'ṷ' => 'u',     // 0x1E77
        'ẑ' => 'z',     // 0x1E91
        'ấ' => 'a',     // 0x1EA5
        'ầ' => 'a',     // 0x1EA7
        'ẩ' => 'a',     // 0x1EA9
        'ẫ' => 'a',     // 0x1EAB
        'ậ' => 'a',     // 0x1EAD
        'ế' => 'e',     // 0x1EBF
        'ề' => 'e',     // 0x1EC1
        'ể' => 'e',     // 0x1EC3
        'ễ' => 'e',     // 0x1EC5
        'ệ' => 'e',     // 0x1EC7
        'ố' => 'o',     // 0x1ED1
        'ồ' => 'o',     // 0x1ED3
        'ổ' => 'o',     // 0x1ED5
        'ỗ' => 'o',     // 0x1ED7
        'ộ' => 'o'      // 0x1ED9
    );

    private $options;

    public function __construct($options = null) {
        $this->options = array_merge($options ?: array(), array(
                self::ENCODING => 'UTF-8'
            ));
    }

    public function transform($input, $maxLength = 80) {
        if (is_null($input)) {
            return '';
        }

        if ($maxLength < 1) {
            $maxLength = PHP_INT_MAX;
        }

        $dash = false;
        $length = $this->strlen($input);
        $output = array();

        for ($i = 0; $i < $length && count($output) < $maxLength; ++$i) {
            $c = $this->getChar($input, $i);

            if (($c >= 'a' && $c <= 'z') ||
                ($c >= '0' && $c <= '9')) {
                $output[] = $c;
                $dash = false;
            } else if ($c >= 'A' && $c <= 'Z') {
                $output[] = strtolower($c);
                $dash = false;
            } else if (strpos(" ,./\\-_=", $c) !== false) {
                if ($dash || count($output) <= 0) {
                    continue;
                }
                $output[] = '-';
                $dash = true;
            } else if ($c == '&') {
                if (!$dash && count($output) > 0) {
                    $output[] = '-';
                }
                $output[] = 'and-';
                $dash = true;
            } else if ($c > chr(128)) {
                $lower = $this->lowercase($c);
                if (array_key_exists($lower, self::$charMap)) {
                    $output[] = self::$charMap[$lower];
                    $dash = false;
                }
            }
        }

        if ($dash) {
            array_pop($output);
        }

        return implode($output);
    }

    private function strlen($str) {
        return mb_strlen($str, $this->options[self::ENCODING]);
    }

    private function getChar($str, $i) {
        return mb_substr($str, $i, 1, $this->options[self::ENCODING]);
    }

    private function lowercase($str) {
        return mb_strtolower($str, $this->options[self::ENCODING]);
    }

    public static function defaultTransform($input) {
        static $dasherize;
        if (is_null($dasherize)) {
            $dasherize = new Dasherize();
        }
        return $dasherize->transform($input);
    }
}

function Dasherize($input) {
    return Dasherize::defaultTransform($input);
}