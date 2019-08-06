<?php
namespace Digiwallet\Packages\Transaction\Client;

use Psr\Http\Message\RequestInterface as Request;
use GuzzleHttp\ClientInterface as GuzzleClient;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Client
 * @package Digiwallet\Packages\Transaction\Client
 */
class Client implements ClientInterface
{
    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * Client constructor.
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function doRequest(Request $request): Response
    {
        try {
            $response = $this->client->send($request);

        } catch (GuzzleException $exception) {

        }
    }
}
