<?php

namespace Tests;

trait Requester
{
    public function postRequest(string $url, array $params)
    {
        $http = new \GuzzleHttp\Client(array(
            'cookies' => true
        ));


        try{
            $response = $http->post('http://api.pizzapp.test/'.$url,[
                'form_params' => $params
            ]);

            return response()->json($response->getBody(),$response->getStatusCode());
        } catch (\GuzzleHttp\Exception\BadResponseException $e)
        {
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
}

?>
