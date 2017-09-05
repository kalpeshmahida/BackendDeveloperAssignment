<?php

namespace Neo;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Neo\HttpClient\HttpClientInterface;
use Neo\HttpClient\GuzzleHttpClient;
use Neo\Exception\ApiException;

class Client
{
    /**
     * API base uri
     */
    const API_BASE_URI = 'https://api.nasa.gov/neo/rest/';

    /**
     * API Version
     */
    const DEFAULT_API_VERSION = 'v1';

    /**
     * Request default timeout
     */
    const DEFAULT_TIMEOUT = 60;

    /**
     * @var array
     */
    public static $allowedMethod = ['GET'];

    /**
     * @var string NEO API-KEY
     */
    private $apiKey;

    /**
     * @var HttpClient client
     */
    private $client;

    /**
     * @var ResponseInterface|null
     */
    private $lastResponse;

    public function __construct($apiKey, HttpClientInterface $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->client = $httpClient ?: $this->defaultHttpClient();
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return null|ResponseInterface
     */
    public function get($uri, array $params = [])
    {
        return $this->send('GET', $uri, null, $params);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param mixed $body
     * @param array $query
     * @param array $headers
     * @param array $options
     *
     * @return null|ResponseInterface
     */
    public function send($method, $uri, $body = null, array $query = [], array $headers = [], array $options = [])
    {
        $this->validateMethod($method);
        $options = $this->enhanceOptions($body, $query, $headers, $options);
        try {
            $this->lastResponse = $this->client->send($method, $uri, $body, $query, $headers, $options);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
        //$this->validateResponse($this->lastResponse);

        return $this->lastResponse;
    }

    /**
     * @return HttpClientInterface
     */
    private function defaultHttpClient()
    {
        return new GuzzleHttpClient();
    }

    private function enhanceOptions($body = null, array $query = [], array $headers = [], array $options = [])
    {
        // Add api key
        if (!isset($query['api_key'])) {
            $query['api_key'] = $this->apiKey;
        }

        $options[RequestOptions::QUERY] = $query;

        return $options;
    }

    /**
     * @param $method
     *
     * @throw \InvalidArgumentException
     */
    private function validateMethod($method)
    {
        if (!in_array(strtoupper($method), self::$allowedMethod)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not in the allowed methods "%s"', $method, implode(', ', self::$allowedMethod))
            );
        }
    }

    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200) {

        }
    }
}