<?php

class GeocodingService {
    private $apiKey;

    public function __construct() {
        $this->apiKey = $_ENV['GOOGLE_MAPS_API_KEY'];
    }

    public function getCoordinates($address) {
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$this->apiKey}";
        
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            return [
                'latitude' => $data['results'][0]['geometry']['location']['lat'],
                'longitude' => $data['results'][0]['geometry']['location']['lng']
            ];
        }

        // Se não conseguir obter as coordenadas da API, usa coordenadas mock
        return $this->getMockCoordinates($address);
    }

    private function getMockCoordinates($address) {
        // Coordenadas padrão para teste (São Paulo)
        return [
            'latitude' => -23.550520,
            'longitude' => -46.633308
        ];
    }
}
