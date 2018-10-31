<?php

namespace Model\Service;

use Model\Entity\Shared;
use Model\Entity\Tag;
use Model\Service\Facade\CollectionToArrayCovertor;
use Model\Entity\ResponseBootstrap;
use Model\Mapper\TagsMapper;
use Monolog\Logger;

class TagService
{
    private $tagsMapper;
    private $configuration;
    private $monolog;

    public function __construct(TagsMapper $tagsMapper)
    {
        $this->tagsMapper = $tagsMapper;
        $this->configuration = $tagsMapper->getConfiguration();
        $this->monolog = new Logger('monolog');
    }


    /**
     * Get tags
     *
     * @return ResponseBootstrap
     */
    public function getTags():ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // call mapper for data
            $data = $this->tagsMapper->getTags();

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayCovertor($data);
            $convertedData = $facade->convertData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get tags service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get tags by ids
     *
     * @param string $ids
     * @return ResponseBootstrap
     */
    public function getTagsByIds(string $ids):ResponseBootstrap {

        try {
            // create new response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Tag();
            $entity->setIds($ids);

            // call mapper for data
            $data = $this->tagsMapper->getTagsByIds($entity);

            // convert collection data to an array in a facade object
            $facade = new CollectionToArrayCovertor($data);
            $convertedData = $facade->convertData();

            // check data and set appropriate response
            if(!empty($convertedData)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData($convertedData);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Get tags by ids service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Delete tag by id
     *
     * @param int $id
     * @return ResponseBootstrap
     */
    public function deleteTag(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Tag();
            $entity->setId($id);

            // create shared entity
            $shared = new Shared();

            // get response
            $result = $this->tagsMapper->deleteTag($entity, $shared);

            // check data and set response
            if ($result->getState() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            } else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Delete tag service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Add tag
     *
     * @param string $name
     * @return ResponseBootstrap
     */
    public function addTag(string $name):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new Tag();
            $entity->setName($name);

            // create shared entity
            $shared = new Shared();

            // get response
            $result = $this->tagsMapper->addTag($entity, $shared);

            // check data and set response
            if ($result->getState() === 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            } else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return response
            return $response;

        }catch (\Exception $e){
            // write monolog entry
            $this->monolog->addError('Add tag service: ' . $e);

            // set response on failure
            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }

}