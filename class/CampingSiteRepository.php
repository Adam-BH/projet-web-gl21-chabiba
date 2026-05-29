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
            $site = CampingSite::fromRow($r);
            $site->images = $this->getImagesForSite($site->id);
            $sites[] = $site;
        }
        return $sites;
    }

    public function findById($id): ?CampingSite{
        $row = parent::findById($id);
        if (!$row) return null;
        $site = CampingSite::fromRow($row);
        $site->images = $this->getImagesForSite($site->id);
        return $site;
    }

    /** @return string[] */
    public function getImagesForSite($siteId): array{
        if (!$siteId) return [];
        $stmt = $this->db->prepare("select path from camping_site_images where site_id = ? order by sort_order asc");
        $stmt->execute([$siteId]);
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        $imgs = [];
        foreach($rows as $r) $imgs[] = $r->path;
        return $imgs;
    }
}
