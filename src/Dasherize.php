<?php

namespace zzzzbov\Dasherize;

class Dasherize {
    private $options;

    public function __construct($options = null) {
        $this->options = array_merge($options ?: array(), array(

            ));
    }

    public function transform($input) {
        //return $input;
    }
}

function Dasherize($input) {
    static $dasherize;
    if (is_null($dasherize)) {
        $dasherize = new Dasherize();
    }
    return $dasherize->transform($input);
}