<?php
class BookingRepository extends Repository{
    public function __construct(){
        parent::__construct('bookings');
    }

    /** Get bookings for a site overlapping a date range */
    public function getBookingsForSiteBetween(int $siteId, string $startDate, string $endDate): array{
        $stmt = $this->db->prepare(
            "SELECT * FROM bookings WHERE site_id = ? AND NOT (end_date < ? OR start_date > ?)");
        $stmt->execute([$siteId, $startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /** Sum people for overlapping bookings */
    public function getPeopleCountForSiteBetween(int $siteId, string $startDate, string $endDate): int{
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(people),0) as total FROM bookings WHERE site_id = ? AND NOT (end_date < ? OR start_date > ?)");
        $stmt->execute([$siteId, $startDate, $endDate]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getByUser(string $userId): array{
        $stmt = $this->db->prepare(
            "SELECT b.*, cs.name AS site_name FROM bookings b LEFT JOIN camping_sites cs ON b.site_id = cs.id WHERE b.user_id = ? ORDER BY b.start_date DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteById(int $id): void{
        $stmt = $this->db->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
    }
}
