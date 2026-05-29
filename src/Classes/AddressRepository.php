<?php
class AddressRepository extends Repository{
    const tableName = "adresses";
    public function __construct(){
        return parent::__construct(self::tableName);
    }
}