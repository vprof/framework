<?php

namespace Framework\Validation\Filter;

class NotBlank {

    public function check($var) {
        return (iconv_strlen($var) == 0) ? ['error' => 'must be not blank'] : true;
    }
}
?>