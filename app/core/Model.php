<?php

namespace Application\Core;

class Model {

    protected $errors = array();
    protected $successful = array();

    public function getErrors() {
        return $this->errors;
    }
    
    public function getSuccessful()
    {
        return $this->successful;
    }

}
