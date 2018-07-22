<?php

namespace AppBundle\Service;

class adresseUtils{


public function getAdresse($city){
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://geo.api.gouv.fr/communes?nom='.$city);
    $contents = json_decode($response->getBody()->getContents(), true);

    return $contents;

}
}