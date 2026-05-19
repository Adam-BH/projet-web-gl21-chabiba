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
}
