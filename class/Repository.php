<?php
abstract class Repository implements IRepository{
    protected $db;
    public function __construct(protected $tableName){
        $this->db=ConnexionDB::getInstance();
    }

public function findAll() {
    $query = "SELECT * FROM {$this->tableName}";
    $res = $this->db->query($query);
    return $res->fetchAll(PDO::FETCH_OBJ);
}

    public function findById($id){
        $response=$this->db->prepare("select * from {$this->tableName} where id = ? ;");
        $response->execute([$id]);
        return $response->fetch(PDO::FETCH_OBJ);
    }
    public function delete($id) {
        $response = $this->db->prepare(query: "delete from {$this->tableName} where id = ?");
        $response->execute([$id]);
    }

public function create($params) {
    $keys = array_keys($params);
    $keyString = implode('`, `', $keys);
    $paramString = implode(', ', array_fill(0, count($keys), '?'));
    $query = "INSERT INTO `{$this->tableName}` (`{$keyString}`) VALUES ({$paramString})";
    $response = $this->db->prepare(query: $query);
    $response->execute(array_values($params));
}
}