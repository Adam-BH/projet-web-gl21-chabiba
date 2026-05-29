<?php

namespace App\DataFixtures;

use App\Entity\CampingSite;
use App\Entity\CampingSiteImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampingSiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sites = [
            [
                'name' => 'Lake View Camp',
                'description' => 'Nice campsite by the lake.',
                'capacity' => 60,
                'city' => 'Lakecity',
                'lat' => 45.123,
                'lon' => 3.123,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/lakeview-1.jpg', 'sort' => 0],
                    ['path' => 'assets/Images/lakeview-2.jpg', 'sort' => 1],
                ],
            ],
            [
                'name' => 'Pine Forest Camp',
                'description' => 'Shaded tentsites among pines.',
                'capacity' => 40,
                'city' => 'Forestville',
                'lat' => 46.234,
                'lon' => 4.234,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/pine-1.jpg', 'sort' => 0],
                ],
            ],
            [
                'name' => 'Riverside Meadow',
                'description' => 'Open meadow sites next to a slow river, great for families.',
                'capacity' => 80,
                'city' => 'Meadowville',
                'lat' => 45.532,
                'lon' => 3.432,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/river-1.jpg', 'sort' => 0],
                ],
            ],
            [
                'name' => 'Highland Ridge',
                'description' => 'Quiet ridge-top sites with panoramic views and wind-sheltered pitches.',
                'capacity' => 30,
                'city' => 'Ridgeton',
                'lat' => 46.001,
                'lon' => 4.001,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/highland-1.jpg', 'sort' => 0],
                ],
            ],
            [
                'name' => 'Sunny Glen',
                'description' => 'South-facing glen with easy access to walking trails.',
                'capacity' => 50,
                'city' => 'Glenburg',
                'lat' => 44.981,
                'lon' => 3.987,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/sunny-1.jpg', 'sort' => 0],
                ],
            ],
            [
                'name' => 'Oak Hollow Camp',
                'description' => 'Under oak canopy with picnic areas and fire rings.',
                'capacity' => 35,
                'city' => 'Oaktown',
                'lat' => 45.221,
                'lon' => 3.554,
                'image' => '',
                'images' => [
                    ['path' => 'assets/Images/oak-1.jpg', 'sort' => 0],
                ],
            ],
        ];

        foreach ($sites as $data) {
            $site = new CampingSite();
            $site->setName($data['name'])
                ->setDescription($data['description'])
                ->setCapacity($data['capacity'])
                ->setCity($data['city'])
                ->setLat($data['lat'])
                ->setLon($data['lon'])
                ->setImage($data['image']);

            $manager->persist($site);

            foreach ($data['images'] as $imgData) {
                $image = new CampingSiteImage();
                $image->setSite($site)
                    ->setPath($imgData['path'])
                    ->setSortOrder($imgData['sort']);
                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
