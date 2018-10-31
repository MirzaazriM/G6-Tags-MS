<?php

namespace Model\Mapper;

use Model\Entity\Tag;
use Model\Entity\TagsCollection;
use Model\Entity\Shared;
use PDO;
use PDOException;
use Component\DataMapper;

class TagsMapper extends DataMapper
{
    public function getConfiguration()
    {
        return $this->configuration;
    }


    /**
     * Get all tags
     *
     * @return TagsCollection
     */
    public function getTags():TagsCollection {
        // create response object
        $tagsCollection = new TagsCollection();

        try{
            // set database instructions
            $sql = "SELECT id, name FROM tags";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // check if anything is fetched from database
            if($statement->rowCount() > 0){
                // if yes loop through data and set it to tags collection
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                    // create entity and set its values
                    $tag = new Tag();
                    $tag->setId($row['id']);
                    $tag->setName($row['name']);

                    // add entity to the collection
                    $tagsCollection->addEntity($tag);
                }
            }

        }catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get tags mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get tags mapper: ' . $e);
            }
        }

        // return data
        return $tagsCollection;
    }


    /**
     * Get tags by ids
     *
     * @param Tag $tag
     * @return TagsCollection
     */
    public function getTagsByIds(Tag $tag):TagsCollection {
        // create response object
        $tagsCollection = new TagsCollection();

        try{
            // set database instructions
            $sql = "SELECT id, name FROM tags WHERE id IN (" . $tag->getIds() . ")";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // check if anything is fetched from database
            if($statement->rowCount() > 0){
                // if yes loop through data and set it to tags collection
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
                    // create entity and set its values
                    $tag = new Tag();
                    $tag->setId($row['id']);
                    $tag->setName($row['name']);

                    // add entity to the collection
                    $tagsCollection->addEntity($tag);
                }
            }

        }catch (PDOException $e){
            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Get tags by ids mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Get tags by ids mapper: ' .  $e);
            }
        }

        // return data
        return $tagsCollection;
    }


    /**
     * Delete tag
     *
     * @param Tag $tag
     * @param Shared $shared
     * @return Shared
     */
    public function deleteTag(Tag $tag, Shared $shared):Shared {

        try{
            // set database instructions
            $sql = "DELETE 
                        t.*,
                        st.*,
                        pt.*
                    FROM tags AS t 
                    LEFT JOIN supplement_tags AS st ON st.tag_id = t.id
                    LEFT JOIN product_tags AS pt ON pt.tag_id = t.id
                    WHERE t.id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $tag->getId()
            ]);

            // set response state
            if($statement->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        }catch(PDOException $e){
            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Delete tag mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Delete tag mapper: ' . $e);
            }
        }

        // return response
        return $shared;
    }


    /**
     * Add tag
     *
     * @param Tag $tag
     * @param Shared $shared
     * @return Shared
     */
    public function addTag(Tag $tag, Shared $shared):Shared {

        try {
            // set database instructions
            $sql = "INSERT INTO tags 
                      (name) 
                      VALUES (?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $tag->getName(),
            ]);

            // set response state
            if ($statement->rowCount() > 0){
                $shared->setState(200);
            }else {
                $shared->setState(304);
            }

        }catch (PDOException $e){
            // set state
            $shared->setState(304);

            // get error code
            $code = $e->errorInfo[1];

            // set appropriate monolog entry dependeng on error code value
            if((int)$code >= 1000 && (int)$code <= 1749){
                $this->monolog->addError('Add tag mapper: ' . $e);
            }else {
                $this->monolog->addWarning('Add tag mapper: ' . $e);
            }
        }

        // return response
        return $shared;
    }

}