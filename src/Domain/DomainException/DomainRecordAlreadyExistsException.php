<?php

namespace App\Domain\DomainException;

use Slim\Exception\HttpSpecializedException;

class DomainRecordAlreadyExistsException extends HttpSpecializedException {

    protected $code = 406;
    protected $message = 'Conflict.';
    protected $title = '406 Conflict';
    protected $description = 'Domain Record already exists';

}

?>