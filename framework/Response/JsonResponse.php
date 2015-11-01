<?php

namespace Framework\Response;

class ResponseJson extends AbstractResponse {

    protected $headers = array('Content-Type: application/json');
    
    public function __construct() {
        parent::__construct();
        $this->addHeader($this->headers);
        $this->setContent(json_encode($this->getContent()));
        
    }

}

?>