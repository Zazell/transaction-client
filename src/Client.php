<?php
namespace Digiwallet\Packages\Transaction\Client;

use Digiwallet\Packages\Transaction\Client\Request\CheckTransactionInterface as CheckTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Request\CreateTransactionInterface as CreateTransactionRequest;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class Client
 * @package Digiwallet\Packages\Transaction\Client
 */
class Client extends GuzzleClient implements ClientInterface
{
    /**
     * @var string
     */
    private $bearer;

    /**
     * Client constructor.
     * @param string $bearer
     * @param string $url
     */
    public function __construct(string $bearer, string $url)
    {
        parent::__construct(['base_uri' => $url]);
        $this->bearer = $bearer;
    }

    /**
     * @param CreateTransactionRequest $createTransaction
     * @return Response
     * @throws GuzzleException
     */
    public function createTransaction(CreateTransactionRequest $createTransaction): Response
    {
        $request = $createTransaction
            ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
            ->withAddedHeader('Content-Type', 'application/json');

        return $this->send($request);
    }

    /**
     * @param CheckTransactionRequest $checkTransaction
     * @return Response
     * @throws GuzzleException
     */
    public function checkTransaction(CheckTransactionRequest $checkTransaction): Response
    {
        $request = $checkTransaction
            ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
            ->withAddedHeader('Content-Type', 'application/json');

        return $this->send($request);
    }
}
