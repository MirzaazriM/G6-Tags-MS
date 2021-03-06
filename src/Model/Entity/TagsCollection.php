<?php

namespace Model\Entity;

use Component\Collection;
use Model\Contract\HasId;

class TagsCollection extends Collection
{
    private $statusCode;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function buildEntity(): HasId
    {
        // TODO: Implement buildEntity() method.
    }

    public function addEntity(HasId $entity, $key = null)
    {
        return parent::addEntity($entity, $key); // TODO: Change the autogenerated stub
    }
}
