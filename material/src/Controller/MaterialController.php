<?php

namespace Drupal\material\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Serialization\Json;

/**
 * Returns weather information based on city id.
 */
class MaterialController extends ControllerBase {
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

		$apiURL = $API_URL.'id='.$city_id.'&lang=en&units=metric&appid='.$API_KEY;

		$cURLConnection = curl_init();
		curl_setopt($cURLConnection, CURLOPT_HEADER, 0);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cURLConnection, CURLOPT_URL, $apiURL);
		curl_setopt($cURLConnection, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cURLConnection, CURLOPT_VERBOSE, 0);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		
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
}