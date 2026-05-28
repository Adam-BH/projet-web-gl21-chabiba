<?php
class CampingSite{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?int $capacity = null,
        public ?string $city = null,
        public ?float $lat = null,
        public ?float $lon = null,
        public ?string $image = null,
        public ?string $created_at = null,
    ){}

    public static function fromRow(object|array $row): CampingSite{
        if (is_array($row)) $row = (object)$row;
        return new CampingSite(
            id: isset($row->id) ? (int)$row->id : null,
            name: $row->name ?? null,
            description: $row->description ?? null,
            capacity: isset($row->capacity) ? (int)$row->capacity : null,
            city: $row->city ?? null,
            lat: isset($row->lat) ? (float)$row->lat : null,
            lon: isset($row->lon) ? (float)$row->lon : null,
            image: $row->image ?? null,
            created_at: $row->created_at ?? null,
        );
    }
}
