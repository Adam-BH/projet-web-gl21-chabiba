<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weather', name: 'app_weather')]
class WeatherController extends AbstractController
{
    #[Route('', name: '')]
    public function index(Request $request, WeatherService $weather): Response
    {
        $city     = trim($request->query->get('city', 'Tunis'));
        $forecast = $weather->getForecast($city);

        $todayShort   = strtoupper(date('D'));
        $selectedShort = strtoupper($request->query->get('day', $todayShort));

        $selectedDay = null;
        foreach ($forecast['days'] as $day) {
            if ($day['short'] === $selectedShort) {
                $selectedDay = $day;
                break;
            }
        }
        if ($selectedDay === null) {
            $selectedDay = $forecast['days'][0];
        }

        return $this->render('weather/index.html.twig', [
            'forecast'    => $forecast,
            'selectedDay' => $selectedDay,
            'city'        => $city,
        ]);
    }
}
