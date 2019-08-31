<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class DomainRecordValidator extends DomainException
{
    protected $code = 400;
    protected $message = 'Domain Record fields invalid';

    /** 
     * @var array
    */
    protected $data;

    /**
     * @param string $message
     * @param int $code
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($this->message);
        $this->data = $data;
    }

    /**
     * Get the value of data
     *
     * @return array
     */ 
    public function getData()
    {
        return $this->data;
    }
}
