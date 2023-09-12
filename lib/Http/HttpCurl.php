<?php

namespace Http;

class HttpCurl implements ICurlWrapper
{
    public function post(string $url, array $params)
    {
        $response = false;
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            $response = curl_exec($curl);
            curl_close($curl);
        }


        return $response;
    }

    public function get(string $url, array $params)
    {
        $response = false;

        $url = sprintf('%s?%s', $url, http_build_query($params)); // concat url + get params

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
        }

        return $response;
    }
}