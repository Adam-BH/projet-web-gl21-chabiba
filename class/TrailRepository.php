<?php
class TrailRepository extends Repository {
    public function __construct() {
        parent::__construct('trails');
    }

    public function findByLevel($level) {
        $req = "SELECT * FROM {$this->tableName} WHERE level = ?";
        $stmt = $this->db->prepare($req);
        $stmt->execute([$level]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function findWithFilters($level, $age, $group_size) {
        $query = "SELECT * FROM {$this->tableName} WHERE 1=1";
        $params = [];
        
        if ($level !== 'all') {
            $query .= " AND level = ?";
            $params[] = $level;
        }
        if (!empty($age)) {
            $query .= " AND min_age <= ?";
            $params[] = $age;
        }
        if (!empty($group_size)) {
            $query .= " AND (max_group_size IS NULL OR max_group_size >= ?)";
            $params[] = $group_size;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
