<?php

namespace Framework\Response;

use Framework\DI\Service;

class ResponseRedirect extends AbstractResponse {

    public $code;
    public $replace;
    public $url;

    public function __construct($url, $replace = true, $code = 307) {
        
        $this->url = $url;
        $this->replace = $replace;
        $this->code = $code;
        $this->send();
    }

    public function send() {
        $request = Service::get('request');
        header('Referer: ' . $request->getUri());
        header('Location: ' . $this->url, $this->replace, $this->code);
        exit();
    }

}

?>