<?php
/**
 * Class responsible of Modgen API calls through CURL
 * @package Recommender/Api/Helpers
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-25
 * @license
 */

namespace Recommender\Api\Helpers;

class Curl
{
    /*
     * @var mixed - Curl instance
    */
    private $curl = '';

    /**
     * Method will encode post query to right format
     * @param array $query - query data to encode
     * @param string $postType - query type
     * @return mixed
     */
    public function encodePostQuery(array $query, $postType = 'query')
    {
        $output = array('data', 'url', 'header');

        switch (strtolower($postType)) {
            case 'query':
                $output['data'] = http_build_query($query);
                $output['url'] = false;
                $output['header'] = false;
                break;
            case 'json':
                $output['data'] = json_encode($query);
                $output['url'] = false;
                $output['header'] = 'json';
                break;
            case 'get':
                $output['data'] = false;
                $output['url'] = http_build_query($query);
                $output['header'] = false;
                break;
            case 'batch':
                $output['data'] = json_encode($query);
                $output['url'] = false;
                $output['header'] = 'json';
                break;
        }
        return $output;
    }

    /**
     * Method will add CURL request
     * @param string $method - method to call
     * @param string $url - URL to call
     * @param string $postQueryData - data to be send via POST in key=value URL format (encode it via example http_build_query function
     * @return mixed
     */
    public function addCall($method, $url, array $postQueryData = array(), $postType = 'QUERY')
    {
        $this->curl = curl_init();
        $post = self::encodePostQuery($postQueryData, $postType);

        $i = 0;
        switch ($method) {
            case "GET":
                break;
            case "HEAD":
                break;
            case "POST":
                curl_setopt($this->curl, CURLOPT_POST, 1);
                if ($postQueryData) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post['data']);
                        file_put_contents(__DIR__ . '../../../../../test/store/' . $i . '.json', $post['data']);
                        $i++;
                }
                break;
            case "PUT":
                curl_setopt($this->curl, CURLOPT_PUT, 1);
                break;
            case "DELETE":
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "TRACE":
                break;
            case "OPTIONS":
                break;
            case "CONNECT":
                break;
            case "PATCH":
                break;
        }

        if ($post['header'] === 'json') {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post['data']))
            );
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * Method will execute CURL request
     * @return JSON
     */
    public function process()
    {
        $result = curl_exec($this->curl);
        curl_close($this->curl);

        return $result;
    }
}