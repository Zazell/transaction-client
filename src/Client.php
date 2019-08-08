<?php
namespace Digiwallet\Packages\Transaction\Client;

use Digiwallet\Packages\Transaction\Client\Request\CheckTransactionInterface as CheckTransaction;
use Digiwallet\Packages\Transaction\Client\Request\CreateTransactionInterface as CreateTransaction;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class Client
 * @package Digiwallet\Packages\Transaction\Client
 */
class Client extends GuzzleClient implements ClientInterface
{
    /**
     * Client constructor.
     * @param CreateTransaction $createTransaction
     * @param CheckTransaction $checkTransaction
     */
    public function __construct(CreateTransaction $createTransaction, CheckTransaction $checkTransaction)
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function doRequest(Request $request): Response
    {
        try {
            $response = $this->send($request);

        } catch (GuzzleException $exception) {

        }
    }


    public function createTransaction()
    {

    }
}
