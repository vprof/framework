<?php

namespace Framework\Validation\Filter;

class Length {

    public $min;
    public $max;

    public function __construct($min, $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function check($var) {
        $length = iconv_strlen($var);
        if ($length < $this->min)
            return ['error' => 'must be more than ' . $this->min . ' characters'];
        if ($length > $this->max)
            return ['error' => 'must be less than ' . $this->max . ' characters'];
        return true;
    }
}
?>