<?php

namespace Framework\Validation;

use Framework\Model\ActiveRecord;
use Framework\Exception\ValidationException;

class Validator {

    protected $errors = NULL;
    protected $rules;
    protected $objVars;

    public function __construct($post) {
        try {
            if ($post instanceof ActiveRecord) {
                $this->rules = $post->getRules();
                $this->objVars = get_object_vars($post);
            } else {
                throw new ValidationException($post . ' - is not instance of ActiveRecord');
            }
        } catch (ValidationException $e) {
            echo 'ERROR - ' . $e->getMessage();
        }
    }

    public function isValid() {
        foreach ($this->rules as $name => $filters) {
            foreach ($filters as $filter) {
                $result = $filter->check($this->objVars[$name]);
                if (is_array($result)) {
                    $this->errors[$name] = 'Error, ' . $name . ' ' . $result['error'];
                }
            }
        }
        return ($this->errors == NULL);
    }

    public function getErrors() {
        return $this->errors;
    }

}
?>