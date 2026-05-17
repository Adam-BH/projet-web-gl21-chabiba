<?php
class PostRepository extends Repository{
    const tableName = "posts";
    public function __construct(){
        return parent::__construct(self::tableName);
    }
}