<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 8/31/18
 * Time: 11:09 AM
 */

namespace Model\Service\Facade;


use Model\Entity\TagsCollection;

class CollectionToArrayCovertor
{

    private $collection;

    public function __construct(TagsCollection $collection){
        $this->collection = $collection;
    }


    /**
     * Convert collection to array
     *
     * @return array
     */
    public function convertData():array  {
        // create new array
        $data = [];

        // loop through data
        for($i = 0; $i < count($this->collection); $i++){
            $data[$i]['id'] = $this->collection[$i]->getId();
            $data[$i]['name'] = $this->collection[$i]->getName();
        }

        // return converted data
        return $data;
    }

}