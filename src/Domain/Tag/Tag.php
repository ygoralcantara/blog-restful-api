<?php

namespace App\Domain\Tag;

use JsonSerializable;

class Tag implements JsonSerializable{

    /**
     * Tag ID
     *
     * @var int
     */
    private $id;

    /**
     * Tag name
     *
     * @var string
     */
    private $name;

    /**
     * Tag Constructor
     *
     * @param string $name
     */
    public function __construct($name, $id = 0)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * Get tag name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tag name
     *
     * @param string $name Tag name
     *
     * @return string
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this->name;
    }

    /**
     * Get tag ID
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag ID
     *
     * @param int $id Tag ID
     *
     * @return int
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Json Serialize
     *
     * @return void
     */
    public function jsonSerialize()
    {
        return [
            'id'   => $this->id, 
            'name' => $this->name,
        ];    
    }
}

?>