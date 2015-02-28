<?php

use zzzzbov\Utils;
use zzzzbov\Utils\Dasherize;

class DasherizeTest extends PHPUnit_Framework_TestCase {
    /**
     * @dataProvider dasherizeProvider
     */
    public function testDasherize($input, $output) {
        $this->assertSame($output, Utils\Dasherize($input), "\"{$input}\" should be dasherized as \"{$output}\"");
    }

    /**
     * @dataProvider dasherizeProvider
     */
    public function testDefaultTransform($input, $output) {
        $this->assertSame($output, Dasherize::defaultTransform($input), "\"{$input}\" should be dasherized as \"{$output}\"");
    }

    /**
     * @dataProvider dasherizeMaxLengthProvider
     */
    public function testDasherizeWithMaxLength($input, $output, $length) {
        $this->assertSame($output, Utils\Dasherize($input, $length), "\"{$input}\" should be dasherized as \"{$output}\"");
    }

    public function dasherizeProvider() {
        return array(
            array(null, ''),
            array('', ''),
            array('lorem', 'lorem'),
            array('lorem ipsum', 'lorem-ipsum'),
            array('LOREM IPSUM', 'lorem-ipsum'),
            array('Lorem Ipsum', 'lorem-ipsum'),
            array('lorem ipsum!', 'lorem-ipsum'),
            array('-lorem ipsum', 'lorem-ipsum'),
            array('lorem ipsum-', 'lorem-ipsum'),
            array('-lorem ipsum-', 'lorem-ipsum'),
            array('lorem 123 ipsum', 'lorem-123-ipsum'),
            array('lorem & ipsum', 'lorem-and-ipsum'),
            array('&amp;', 'and-amp'),
            array("lorem'd ipsum", 'loremd-ipsum'),
            array(
                'ÂâẤấẦầẨẩẪẫẬậĈĉḒḓÊêḘḙẾếỀềỂểỄễỆệĜĝĤĥÎîĴĵḼḽṊṋÔôỐốỒồỔổỖỗỘộŜŝṰṱÛûṶṷŴŵŶŷẐẑ',
                'aaaaaaaaaaaaccddeeeeeeeeeeeeeegghhiijjllnnoooooooooooossttuuuuwwyyzz'
            ),
        );
    }

    public function dasherizeMaxLengthProvider() {
        return array(
            array(null, '', 80),
            array('', '', 80),
            array('lorem', 'lorem', 80),
            array('lorem', 'lorem', 5),
            array('lorem', 'lore', 4),
            array('lorem', 'lor', 3),
            array('lorem', 'lo', 2),
            array('lorem', 'l', 1),
            array('lorem', 'lorem', 0)
        );
    }
}

