<?php
class CampingSiteRepository extends Repository{
    public function __construct(){
        parent::__construct('camping_sites');
    }

    /** @return CampingSite[] */
    public function findAll(): array{
        $rows = parent::findAll();
        $sites = [];
        foreach($rows as $r){
            $sites[] = CampingSite::fromRow($r);
        }
        return $sites;
    }

    public function findById($id): ?CampingSite{
        $row = parent::findById($id);
        if (!$row) return null;
        return CampingSite::fromRow($row);
    }
}
