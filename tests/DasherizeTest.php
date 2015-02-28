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
}

