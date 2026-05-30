<?php
/**
 * Pulls 7-day forecast from Open-Meteo (no API key required).
 * Geocodes the city name first, then asks for daily values.
 * Returns a normalized array the view can render without thinking.
 *
 * Fixes applied:
 *  - codeIcon()  : proper icon per weather category (was always the same PNG)
 *  - codeLabel() : added missing WMO codes 56,57,66,67,77,85,86
 *  - fetchJson() : added User-Agent header (some proxies reject bare requests)
 *  - Caching     : file-based 1-hour cache so the API isn't hit on every page load
 *  - fallback    : country no longer hardcoded to "Tunisia"
 */
class WeatherService
{
    private const GEOCODE_URL  = 'https://geocoding-api.open-meteo.com/v1/search';
    private const FORECAST_URL = 'https://api.open-meteo.com/v1/forecast';
    private const CACHE_TTL    = 3600;          // seconds (1 hour)
    private const CACHE_DIR    = __DIR__ . '/cache/weather/';

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    public function getForecast(string $city): array
    {
        $cacheKey = 'forecast_' . preg_replace('/[^a-z0-9]/i', '_', strtolower($city));
        $cached   = $this->cacheGet($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $location = $this->geocode($city);
        if ($location === null) {
            return $this->fallbackForecast($city);
        }

        return $this->fetchForecast($location['lat'], $location['lon'], $location['name'], $location['country'], $cacheKey);
    }

    /**
     * Fetch forecast by direct coordinates (skips geocoding).
     * Used when a camping site with known lat/lon is selected.
     */
    public function getForecastByCoords(float $lat, float $lon, string $label = ''): array
    {
        $cacheKey = 'forecast_' . round($lat, 4) . '_' . round($lon, 4);
        $cached   = $this->cacheGet($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        return $this->fetchForecast($lat, $lon, $label ?: 'Location', '', $cacheKey);
    }

    private function fetchForecast(float $lat, float $lon, string $name, string $country, string $cacheKey): array
    {
        $params = http_build_query([
            'latitude'   => $lat,
            'longitude'  => $lon,
            'daily'      => implode(',', [
                'weather_code',
                'temperature_2m_max',
                'temperature_2m_min',
                'precipitation_probability_max',
                'wind_speed_10m_max',
            ]),
            'timezone'     => 'auto',
            'forecast_days' => 7,
        ]);

        $data = $this->fetchJson(self::FORECAST_URL . '?' . $params);
        if ($data === null || !isset($data['daily']['time'])) {
            return $this->fallbackForecast($name);
        }

        $days  = [];
        $daily = $data['daily'];
        $count = count($daily['time']);

        for ($i = 0; $i < $count; $i++) {
            $code = (int)$daily['weather_code'][$i];
            $tMax = round($daily['temperature_2m_max'][$i]);
            $tMin = round($daily['temperature_2m_min'][$i]);

            $days[] = [
                'date'     => $daily['time'][$i],
                'short'    => strtoupper(date('D', strtotime($daily['time'][$i]))),
                'temp'     => $tMax,
                'temp_low' => $tMin,
                'rain'     => (int)$daily['precipitation_probability_max'][$i],
                'wind'     => round($daily['wind_speed_10m_max'][$i]),
                'code'     => $code,
                'label'    => $this->codeLabel($code),
                'icon'     => $this->codeIcon($code),
            ];
        }

        $result = [
            'city'    => $name,
            'country' => $country ?: 'Tunisia',
            'days'    => $days,
            'source'  => 'open-meteo',
        ];

        $this->cacheSet($cacheKey, $result);
        return $result;
    }

    // -------------------------------------------------------------------------
    // Geocoding
    // -------------------------------------------------------------------------

    private function geocode(string $city): ?array
    {
        $params = http_build_query(['name' => $city, 'count' => 1, 'language' => 'en']);
        $data   = $this->fetchJson(self::GEOCODE_URL . '?' . $params);

        if ($data === null || empty($data['results'])) {
            return null;
        }

        $first = $data['results'][0];
        return [
            'name'    => $first['name'],
            'country' => $first['country'] ?? 'Unknown',
            'lat'     => $first['latitude'],
            'lon'     => $first['longitude'],
        ];
    }

    // -------------------------------------------------------------------------
    // HTTP helper
    // -------------------------------------------------------------------------

    private function fetchJson(string $url): ?array
    {
        $context = stream_context_create([
            'http' => [
                'timeout'       => 5,
                'ignore_errors' => true,
                'header'        => "User-Agent: WeatherService/1.0\r\n", // FIX: some servers reject keyless requests without a UA
            ],
            'https' => [
                'timeout'       => 5,
                'ignore_errors' => true,
                'header'        => "User-Agent: WeatherService/1.0\r\n",
            ],
        ]);

        $raw = @file_get_contents($url, false, $context);
        if ($raw === false) {
            return null;
        }

        $json = json_decode($raw, true);
        return is_array($json) ? $json : null;
    }

    // -------------------------------------------------------------------------
    // WMO weather-code helpers  (https://open-meteo.com/en/docs#weathervariables)
    // -------------------------------------------------------------------------

    /**
     * FIX: The original map was missing codes 56, 57 (freezing drizzle),
     * 66, 67 (freezing rain), 77 (snow grains), 85, 86 (snow showers).
     */
    private function codeLabel(int $code): string
    {
        $map = [
            0  => 'Clear sky',
            1  => 'Mostly clear',
            2  => 'Partly cloudy',
            3  => 'Overcast',
            45 => 'Fog',
            48 => 'Rime fog',
            51 => 'Light drizzle',
            53 => 'Drizzle',
            55 => 'Heavy drizzle',
            56 => 'Light freezing drizzle',   // FIX: was missing
            57 => 'Heavy freezing drizzle',   // FIX: was missing
            61 => 'Light rain',
            63 => 'Rain',
            65 => 'Heavy rain',
            66 => 'Light freezing rain',       // FIX: was missing
            67 => 'Heavy freezing rain',       // FIX: was missing
            71 => 'Light snow',
            73 => 'Snow',
            75 => 'Heavy snow',
            77 => 'Snow grains',               // FIX: was missing
            80 => 'Showers',
            81 => 'Heavy showers',
            82 => 'Violent showers',
            85 => 'Light snow showers',        // FIX: was missing
            86 => 'Heavy snow showers',        // FIX: was missing
            95 => 'Thunderstorm',
            96 => 'Storm + hail',
            99 => 'Severe storm',
        ];

        return $map[$code] ?? 'Mixed';
    }

    /**
     * FIX: The original method returned the same PNG for virtually every
     * condition. Now each weather category maps to its own icon file.
     *
     * Expected icon files in your assets folder:
     *   clear-day.png
     *   partly-cloudy.png
     *   cloudy.png
     *   fog.png
     *   drizzle.png
     *   freezing-drizzle.png
     *   rain.png
     *   freezing-rain.png
     *   snow.png
     *   snow-showers.png
     *   showers.png
     *   thunderstorm.png
     *   thunderstorm-hail.png
     */
    private function codeIcon(int $code): string
    {
        // Clear / mostly clear
        if ($code === 0 || $code === 1) {
            return 'clear-day.png';
        }

        // Partly / fully cloudy
        if ($code === 2) {
            return 'partly-cloudy.png';
        }
        if ($code === 3) {
            return 'cloudy.png';
        }

        // Fog
        if ($code === 45 || $code === 48) {
            return 'fog.png';
        }

        // Drizzle
        if ($code === 51 || $code === 53 || $code === 55) {
            return 'drizzle.png';
        }

        // Freezing drizzle
        if ($code === 56 || $code === 57) {
            return 'freezing-drizzle.png';
        }

        // Rain
        if ($code === 61 || $code === 63 || $code === 65) {
            return 'rain.png';
        }

        // Freezing rain
        if ($code === 66 || $code === 67) {
            return 'freezing-rain.png';
        }

        // Snow
        if ($code === 71 || $code === 73 || $code === 75 || $code === 77) {
            return 'snow.png';
        }

        // Rain showers
        if ($code === 80 || $code === 81 || $code === 82) {
            return 'showers.png';
        }

        // Snow showers
        if ($code === 85 || $code === 86) {
            return 'snow-showers.png';
        }

        // Thunderstorms
        if ($code === 95) {
            return 'thunderstorm.png';
        }
        if ($code === 96 || $code === 99) {
            return 'thunderstorm-hail.png';
        }

        return 'cloudy.png'; // safe default
    }

    // -------------------------------------------------------------------------
    // File-based cache  (FIX: original had no caching at all)
    // -------------------------------------------------------------------------

    private function cacheGet(string $key): ?array
    {
        $file = self::CACHE_DIR . $key . '.json';
        if (!file_exists($file)) {
            return null;
        }
        if (time() - filemtime($file) > self::CACHE_TTL) {
            @unlink($file);
            return null;
        }
        $raw = @file_get_contents($file);
        if ($raw === false) {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    private function cacheSet(string $key, array $data): void
    {
        if (!is_dir(self::CACHE_DIR)) {
            @mkdir(self::CACHE_DIR, 0755, true);
        }
        $file = self::CACHE_DIR . $key . '.json';
        @file_put_contents($file, json_encode($data), LOCK_EX);
    }

    // -------------------------------------------------------------------------
    // Fallback (used when the API is unreachable)
    // -------------------------------------------------------------------------

    /**
     * FIX: original hardcoded 'Tunisia' as the country for every city.
     * Now it leaves the country blank/unknown since we genuinely don't know.
     */
    private function fallbackForecast(string $city): array
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
            $date = date('Y-m-d', strtotime("+{$i} day"));
            $s    = $samples[$i];
            $days[] = [
                'date'     => $date,
                'short'    => strtoupper(date('D', strtotime($date))),
                'temp'     => $s['temp'],
                'temp_low' => $s['temp'] - 6,
                'rain'     => $s['rain'],
                'wind'     => $s['wind'],
                'code'     => $s['code'],
                'label'    => $this->codeLabel($s['code']),
                'icon'     => $this->codeIcon($s['code']),
            ];
        }

        return [
            'city'    => $city,
            'country' => 'Unknown',   // FIX: was hardcoded 'Tunisia'
            'days'    => $days,
            'source'  => 'fallback',
        ];
    }
}