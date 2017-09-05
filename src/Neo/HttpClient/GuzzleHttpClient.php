<?php

namespace Neo\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Neo\Client as NeoClient;

/**
 * Class GuzzleHttpClient
 * @package Neo\HttpClient
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * GuzzleHttpClient constructor.
     * @param ClientInterface|null $guzzleClient
     */
    public function __construct(ClientInterface $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient([
            'base_uri' => NeoClient::API_BASE_URI . NeoClient::DEFAULT_API_VERSION,
            'timeout' => NeoClient::DEFAULT_TIMEOUT,
            'connect_timeout' => NeoClient::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function send($method, $uri, $body = null, array $query = [], array $headers = [], array $options = [])
    {
        $path = $this->guzzleClient->getConfig('base_uri')->getPath().$uri;
        if (!empty($body) && (is_array($body) || $body instanceof \JsonSerializable)) {
            $options[RequestOptions::JSON] = $body;
        } else {
            $options[RequestOptions::BODY] = $body;
        }

        return $this->guzzleClient->request($method, $path, $options);
    }
}