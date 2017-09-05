<?php

namespace Neo;

/**
 * Class FeedApi
 * @package Neo
 */
class FeedApi
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private $client;

    /**
     * FeedApi constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get a list of Near Earth Objects within a date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @param bool $detailed
     *
     * @return mixed
     */
    public function get($startDate, $endDate, $detailed = true)
    {
        $detailed = $detailed ? 'true' : 'false';
        $response = $this->client->get(
            '/feed',
            array('start_date' => $startDate, 'end_date' => $endDate, 'detailed' => $detailed)
        );

        return $this->decodeResponse($response);
    }
}