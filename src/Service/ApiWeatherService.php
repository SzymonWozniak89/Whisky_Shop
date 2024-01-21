<?php
namespace App\Service;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;



class ApiWeatherService{
    private $cache;
    public function __construct(
        private HttpClientInterface $client,
        ){
            $this->cache = new FilesystemAdapter();
    }

    public function getWeather()
    {
        $cache = $this->cache->getItem('weather_cache_key2');
        if ($cache->isHit()) {
            return $cache->get();
        }
        try 
        {
            $url = sprintf('http://dataservice.accuweather.com/currentconditions/v1/%d?apikey=%s&language=pl-PL&details=true',274663,'RKQcNZRRwjEwxLdpr3jU6bR7GG3La7qU');
            $response = $this->client->request(
                'GET',
                $url,
            );
            $content = $response->toArray();
        } catch (ClientException $e) {
            return [
                'temp'=> random_int(-10,20),
                'WeatherText'=> 'Pogodnie',
                'Pressure'=>'1023',
                'Icon'=>'38',
                'Time'=> '1701120720',
                'Humidity'=>'65',
                'Wind'=>'14',
            ];
        } 

        $value = $this->cache->get('weather_cache_key2', function (ItemInterface $item) use($content): array {
            $item->expiresAfter(1800);
        
            $computedValue = [
                'temp'=> $content[0]['Temperature']['Metric']['Value'],
                'WeatherText'=> $content[0]['WeatherText'],
                'Pressure'=>$content[0]['Pressure']['Metric']['Value'],
                'Icon'=>str_pad($content[0]['WeatherIcon'], 2, "0", STR_PAD_LEFT),
                'Time'=>$content[0]['EpochTime'],
                'Humidity'=>$content[0]['RelativeHumidity'],
                'Wind'=>$content[0]['Wind']['Speed']['Metric']['Value'],
            ];
            return $computedValue;
        });
        return $value;
    }
}