<?php

namespace Drupal\material\Controller;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Returns weather information based on city id.
 */
class MaterialController { 

/**
   * Retrieves weather information from OpenWeather API.
   *  
   * @param string $city_id
   *   The City ID.
   *
   * @return theme
   *   The weather twig.
   */
	public function currentWeather($city_id) {
		$API_KEY = '91d6ff6cfa05fd51f54773c8dca55123';
		$API_URL = "http://api.openweathermap.org/data/2.5/weather?";
		$API_IMG_URL = "http://openweathermap.org/img/w/";
		
		try {
			$client = \Drupal::httpClient();
			$apiURL = $API_URL.'id='.$city_id.'&lang=en&units=metric&appid='.$API_KEY;
			$response = $client->request('GET', $apiURL, ['verify' => FALSE]);
    		$response = $response->getBody();

			$data = Json::decode($response);
		
			$weatherArr = array(
				'name' => $data['name'],
				'curday' => date('l g:i a'),
				'curdate' => date('jS F, Y'),
				'type' => $data['weather'][0]['description'],
				'imageUrl' => $API_IMG_URL.$data['weather'][0]['icon'].'.png',
				'minTemp' => $data['main']['temp_min'],
				'maxTemp' => $data['main']['temp_max'],
				'humidity' => $data['main']['humidity'].'%',
				'wind' => $data['wind']['speed'].' km/h',
			);
	
			return array(
				'#theme' => 'weather',
				'#items' => $weatherArr,
				'#title' => 'Weather Report'	
			);
		}
		catch (GuzzleException $e) {
			watchdog_exception('current_weather', $e);
			return FALSE;
		}	
	}
}