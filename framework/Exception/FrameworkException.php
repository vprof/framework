<?php

namespace framework\Exception;

class FrameworkException extends \Exception {

    private $code;
    private $message;
    private $log_file;

    public function __construct($message, $code) {

        $logFile = __DIR__ . '/../../app/logs/remove.me';
        $logFile = realpath($logFile);
        if (file_exists($logFile)) {
            $this->log_file = $logFile;
        }
        
        $file = fopen($this->log_file, 'a');
        
        $log_msg = date("[Y-m-d H:i:s]") .
        //        " Code: $code - " .
                " Message: $message\n";

        fwrite($file, $log_msg);

        fclose($file);
        
        parent::__construct($message, $code, NULL);
    }

}
