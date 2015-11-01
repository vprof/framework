<?php

namespace Framework\Response;


class Response extends AbstractResponse {

    protected $headers = array();
    public $type = 'html';
    protected $content;
    protected $msg;
    public $code;

    public function __construct($content, $msg = '', $code = 200) {
        $this->content = $content;
        $this->msg = $msg;
        $this->code = $code;
    }

    public function setHeader($header) {
        $this->headers[] = $header;
    }

    public function getType($type) {
        $this->type = $type;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCode() {
        return $this->code;
    }
    
     public function send()
    {
        $this->setHeader('HTTP/1.1 '.$this->code . ' ' . $this->msg);
        header(implode("\n", $this->headers));
        echo $this->getContent();
    }
 
    public function __toString() {
        $result = $this->getContent();
        return $result.'';
    }

}

?>