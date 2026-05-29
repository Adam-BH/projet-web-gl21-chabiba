<?php

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private const GEOCODE_URL  = 'https://geocoding-api.open-meteo.com/v1/search';
    private const FORECAST_URL = 'https://api.open-meteo.com/v1/forecast';
    private const CACHE_TTL    = 3600;

    public function __construct(
        private readonly HttpClientInterface $http,
        private readonly CacheInterface $cache,
    ) {}

    public function getForecast(string $city): array
    {
        $key = 'weather_' . preg_replace('/[^a-z0-9]/i', '_', strtolower($city));

        return $this->cache->get($key, function (ItemInterface $item) use ($city): array {
            $item->expiresAfter(self::CACHE_TTL);

            $location = $this->geocode($city);
            if ($location === null) {
                return $this->fallback($city);
            }

            $data = $this->fetchJson(self::FORECAST_URL, [
                'latitude'    => $location['lat'],
                'longitude'   => $location['lon'],
                'daily'       => implode(',', [
                    'weather_code',
                    'temperature_2m_max',
                    'temperature_2m_min',
                    'precipitation_probability_max',
                    'wind_speed_10m_max',
                ]),
                'timezone'      => 'auto',
                'forecast_days' => 7,
            ]);

            if ($data === null || !isset($data['daily']['time'])) {
                return $this->fallback($city);
            }

            $days  = [];
            $daily = $data['daily'];
            for ($i = 0, $n = count($daily['time']); $i < $n; $i++) {
                $code   = (int) $daily['weather_code'][$i];
                $days[] = [
                    'date'     => $daily['time'][$i],
                    'short'    => strtoupper(date('D', strtotime($daily['time'][$i]))),
                    'label'    => $this->codeLabel($code),
                    'icon'     => $this->codeIcon($code),
                    'temp'     => (int) round($daily['temperature_2m_max'][$i]),
                    'temp_low' => (int) round($daily['temperature_2m_min'][$i]),
                    'rain'     => (int) $daily['precipitation_probability_max'][$i],
                    'wind'     => (int) round($daily['wind_speed_10m_max'][$i]),
                    'code'     => $code,
                ];
            }

            return [
                'city'    => $location['name'],
                'country' => $location['country'],
                'days'    => $days,
                'source'  => 'open-meteo',
            ];
        });
    }

    private function geocode(string $city): ?array
    {
        $data = $this->fetchJson(self::GEOCODE_URL, ['name' => $city, 'count' => 1, 'language' => 'en']);
        if ($data === null || empty($data['results'])) {
            return null;
        }
        $r = $data['results'][0];
        return [
            'name'    => $r['name'],
            'country' => $r['country'] ?? 'Unknown',
            'lat'     => $r['latitude'],
            'lon'     => $r['longitude'],
        ];
    }

    private function fetchJson(string $url, array $query = []): ?array
    {
        try {
            $response = $this->http->request('GET', $url, [
                'query'   => $query,
                'timeout' => 5,
                'headers' => ['User-Agent' => 'HikiApp/1.0'],
            ]);
            $data = $response->toArray(false);
            return is_array($data) ? $data : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function codeLabel(int $code): string
    {
        return match (true) {
            $code === 0          => 'Clear sky',
            $code === 1          => 'Mostly clear',
            $code === 2          => 'Partly cloudy',
            $code === 3          => 'Overcast',
            in_array($code, [45, 48])          => 'Fog',
            in_array($code, [51, 53, 55])      => 'Drizzle',
            in_array($code, [56, 57])          => 'Freezing drizzle',
            in_array($code, [61, 63, 65])      => 'Rain',
            in_array($code, [66, 67])          => 'Freezing rain',
            in_array($code, [71, 73, 75, 77])  => 'Snow',
            in_array($code, [80, 81, 82])      => 'Showers',
            in_array($code, [85, 86])          => 'Snow showers',
            $code === 95         => 'Thunderstorm',
            in_array($code, [96, 99])          => 'Storm + hail',
            default              => 'Mixed',
        };
    }

    private function codeIcon(int $code): string
    {
        return match (true) {
            in_array($code, [0, 1])            => 'sun-cloud-angled-rain.png',
            $code === 2                        => 'sun-cloud-mid-rain.png',
            $code === 3                        => 'moon-cloud-fast-wind.png',
            in_array($code, [45, 48])          => 'moon-cloud-fast-wind.png',
            in_array($code, [51, 53, 55, 56, 57]) => 'sun-cloud-angled-rain.png',
            in_array($code, [61, 63, 65, 66, 67]) => 'sun-cloud-mid-rain.png',
            in_array($code, [71, 73, 75, 77, 85, 86]) => 'moon-cloud-fast-wind.png',
            in_array($code, [80, 81, 82])      => 'sun-cloud-mid-rain.png',
            default                            => 'sun-cloud-angled-rain.png',
        };
    }

    private function fallback(string $city): array
    {
        $samples = [
            ['temp' => 20, 'rain' => 30,  'code' => 61, 'wind' => 18],
            ['temp' => 21, 'rain' => 60,  'code' => 63, 'wind' => 22],
            ['temp' => 18, 'rain' => 100, 'code' => 65, 'wind' => 35],
            ['temp' => 20, 'rain' => 50,  'code' => 80, 'wind' => 20],
            ['temp' => 22, 'rain' => 20,  'code' => 2,  'wind' => 12],
            ['temp' => 24, 'rain' => 10,  'code' => 1,  'wind' => 10],
            ['temp' => 23, 'rain' => 15,  'code' => 2,  'wind' => 14],
        ];

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date   = date('Y-m-d', strtotime("+{$i} day"));
            $s      = $samples[$i];
            $days[] = [
                'date'     => $date,
                'short'    => strtoupper(date('D', strtotime($date))),
                'label'    => $this->codeLabel($s['code']),
                'icon'     => $this->codeIcon($s['code']),
                'temp'     => $s['temp'],
                'temp_low' => $s['temp'] - 6,
                'rain'     => $s['rain'],
                'wind'     => $s['wind'],
                'code'     => $s['code'],
            ];
        }

        return ['city' => $city, 'country' => 'Unknown', 'days' => $days, 'source' => 'fallback'];
    }
}
