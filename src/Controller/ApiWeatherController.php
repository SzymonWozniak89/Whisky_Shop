<?php

namespace App\Controller;

use App\Service\ApiWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiWeatherController extends AbstractController
{
    #[Route('/weather', name: 'weather', methods: ['GET'])]
    public function getApiWeatherData(ApiWeatherService $apiWeatherService)
    {
        return $this->render('partials/weather.html.twig', [
            'weatherInfo'=>$apiWeatherService->getWeather()
        ]); 
    }

}