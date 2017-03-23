<?php

namespace Application\Core;

class Model {

    protected $errors = [];

    public function getErrors() {
        return $this->errors;
    }

}
