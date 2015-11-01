<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Response;

/**
 *
 * @author v-prof
 */
class AbstractResponse
{
    private $responseCode = null;
    private $headers = array();
    private $statusCode = null;
    private $responseStatus = null;
    private $content = null;
    private $protocolVersion = null;
    /**
     * construct default response with protocol version "html 1.1"
     */
    public function __construct() {
        $this->protocolVersion = 'html 1.1';
    }
    /**
     * set response code
     * @param $code code for setting
     */
    public function setResponseCode($code) {
        $this->responseCode = $code;
    }
    /**
     * get current response code
     * @return response code
     */
    public function getResponseCode() {
        return $this->responseCode;
    }
    /**
     * method use for getting headers of response
     * @return array with headers
     */
    public function getHeaders() {
        return $this->headers;
    }
    /**
     * add header in response
     * @param $header header which need add
     */
    public function addHeader($header) {
        array_push($this->headers,  $header);
    }
    /**
     * set content to response which
     * @param $content content
     */
    public function setContent($content) {
        $this->content = $content;
    }
    /**
     * metnod use for getting content of response
     * @return content of response
     */
    public function getContent() {
        return $this->content;
    }
    private function sendStatus() {
        $header = "HTTP/".$this->protocolVersion." ".$this->responseCode." ".$this->responseStatus;
        header($header, true, $this->responseCode);
    }
    /**
     * method use for sending all headers status head and all headers which is in response
     */
    public function sendHeaders() {
        $this->sendStatus();
        foreach ($this->headers as $key =>$head) {
            header($head,true, $this->statusCode);
        }
    }
    /**
     * method use for sending content which is in response
     */
    public function sendContent() {
        echo $this->content;
    }
    /**
     * method for sending status, headers and content which is in response
     */
    public function send() {
        $this->sendStatus();
        $this->sendHeaders();
        $this->sendContent();
    }
    /**
     * method for setting http protocol version
     * @param $protocol protocol
     */
    public function setProtocolVersion($protocol) {
        $this->protocolVersion = $protocol;
    }
}
?>