<?php

namespace App\Helpers;

class HttpRequest
{

    /**
     * HTTP GET request
     * @param string $url - The URL to send the request to
     * @param array $data - The GET data
     * @param array $headers - The request headers
     * @return mixed - A formatted json response
     */
    public static function get(string $url, array $data = [], array $headers = []) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::buildGetURI($url, $data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * HTTP POST request
     * @param string $url - The URL to send the request to
     * @param array $getParams - The GET data
     * @param array $data - The POST data
     * @param array $headers - The request headers
     * @return mixed - A formatted json response
     */
    public static function post(string $url, array $getParams = [], array $data = [], array $headers = []) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Build a GET URI with the given data
     *
     * @param string $url - The base URL
     * @param array $data - The data to be appended to the URL
     * @return string - The built URI
     */
    private static function buildGetURI(string $url, array $data = []): string
    {
        if (empty($data)) {
            return $url;
        }

        $uri = $url . '?';

        foreach ($data as $key => $value) {
            $uri .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        return substr($uri, 0, -1);
    }

}