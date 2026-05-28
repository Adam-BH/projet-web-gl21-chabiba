<?php
/**
 * Pulls 7-day forecast from Open-Meteo (no API key required).
 * Geocodes the city name first, then asks for daily values.
 * Returns a normalized array the view can render without thinking.
 */
class WeatherService
{
    private const GEOCODE_URL = 'https://geocoding-api.open-meteo.com/v1/search';
    private const FORECAST_URL = 'https://api.open-meteo.com/v1/forecast';

    public function getForecast(string $city): array
    {
        $location = $this->geocode($city);
        if ($location === null) {
            return $this->fallbackForecast($city);
        }

        $params = http_build_query([
            'latitude' => $location['lat'],
            'longitude' => $location['lon'],
            'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max,wind_speed_10m_max',
            'timezone' => 'auto',
            'forecast_days' => 7,
        ]);
        $data = $this->fetchJson(self::FORECAST_URL . '?' . $params);
        if ($data === null || !isset($data['daily']['time'])) {
            return $this->fallbackForecast($city);
        }

        $days = [];
        $daily = $data['daily'];
        $count = count($daily['time']);
        for ($i = 0; $i < $count; $i++) {
            $code = (int)$daily['weather_code'][$i];
            $tMax = round($daily['temperature_2m_max'][$i]);
            $tMin = round($daily['temperature_2m_min'][$i]);
            $days[] = [
                'date' => $daily['time'][$i],
                'short' => strtoupper(date('D', strtotime($daily['time'][$i]))),
                'temp' => $tMax,
                'temp_low' => $tMin,
                'rain' => (int)$daily['precipitation_probability_max'][$i],
                'wind' => round($daily['wind_speed_10m_max'][$i]),
                'code' => $code,
                'label' => $this->codeLabel($code),
                'icon' => $this->codeIcon($code),
            ];
        }

        return [
            'city' => $location['name'],
            'country' => $location['country'],
            'days' => $days,
            'source' => 'open-meteo',
        ];
    }

    private function geocode(string $city): ?array
    {
        $params = http_build_query(['name' => $city, 'count' => 1, 'language' => 'en']);
        $data = $this->fetchJson(self::GEOCODE_URL . '?' . $params);
        if ($data === null || empty($data['results'])) {
            return null;
        }
        $first = $data['results'][0];
        return [
            'name' => $first['name'],
            'country' => $first['country'] ?? '',
            'lat' => $first['latitude'],
            'lon' => $first['longitude'],
        ];
    }

    private function fetchJson(string $url): ?array
    {
        $context = stream_context_create([
            'http' => ['timeout' => 5, 'ignore_errors' => true],
            'https' => ['timeout' => 5, 'ignore_errors' => true],
        ]);
        $raw = @file_get_contents($url, false, $context);
        if ($raw === false) {
            return null;
        }
        $json = json_decode($raw, true);
        return is_array($json) ? $json : null;
    }

    private function codeLabel(int $code): string
    {
        $map = [
            0 => 'Clear sky',
            1 => 'Mostly clear', 2 => 'Partly cloudy', 3 => 'Overcast',
            45 => 'Fog', 48 => 'Rime fog',
            51 => 'Light drizzle', 53 => 'Drizzle', 55 => 'Heavy drizzle',
            61 => 'Light rain', 63 => 'Rain', 65 => 'Heavy rain',
            71 => 'Light snow', 73 => 'Snow', 75 => 'Heavy snow',
            80 => 'Showers', 81 => 'Heavy showers', 82 => 'Violent showers',
            95 => 'Thunderstorm', 96 => 'Storm + hail', 99 => 'Severe storm',
        ];
        return $map[$code] ?? 'Mixed';
    }

    private function codeIcon(int $code): string
    {
        if ($code === 0 || $code === 1) {
            return 'sun-cloud-mid-rain.png';
        }
        if ($code >= 95) {
            return 'sun-cloud-angled-rain.png';
        }
        if ($code >= 61 && $code <= 82) {
            return 'sun-cloud-angled-rain.png';
        }
        if ($code >= 45 && $code <= 55) {
            return 'sun-cloud-mid-rain.png';
        }
        return 'sun-cloud-mid-rain.png';
    }

    private function fallbackForecast(string $city): array
    {
        $days = [];
        $samples = [
            ['temp' => 20, 'rain' => 30, 'code' => 61, 'wind' => 18],
            ['temp' => 21, 'rain' => 60, 'code' => 63, 'wind' => 22],
            ['temp' => 18, 'rain' => 100, 'code' => 65, 'wind' => 35],
            ['temp' => 20, 'rain' => 50, 'code' => 80, 'wind' => 20],
            ['temp' => 22, 'rain' => 20, 'code' => 2, 'wind' => 12],
            ['temp' => 24, 'rain' => 10, 'code' => 1, 'wind' => 10],
            ['temp' => 23, 'rain' => 15, 'code' => 2, 'wind' => 14],
        ];
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+{$i} day"));
            $s = $samples[$i];
            $days[] = [
                'date' => $date,
                'short' => strtoupper(date('D', strtotime($date))),
                'temp' => $s['temp'],
                'temp_low' => $s['temp'] - 6,
                'rain' => $s['rain'],
                'wind' => $s['wind'],
                'code' => $s['code'],
                'label' => $this->codeLabel($s['code']),
                'icon' => $this->codeIcon($s['code']),
            ];
        }
        return [
            'city' => $city,
            'country' => 'Tunisia',
            'days' => $days,
            'source' => 'fallback',
        ];
    }
}
