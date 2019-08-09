<?php
namespace Digiwallet\Packages\Transaction\Client;

use Digiwallet\Packages\Transaction\Client\Request\CheckTransactionInterface as CheckTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Request\CreateTransactionInterface as CreateTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponse;
use Digiwallet\Packages\Transaction\Client\Response\CreateTransactionInterface as CreateTransactionResponse;
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
     * @var string
     */
    private $bearer;

    /**
     * Client constructor.
     * @param string $bearer
     */
    public function __construct(string $bearer, $digiwalletPayUrl = 'https://private-81d23-digiwallettransaction.apiary-mock.com')
    {
        $this->bearer = $bearer;
        parent::__construct(['base_uri' => $digiwalletPayUrl]);
    }

    /**
     * @param CreateTransactionRequest $createTransactionRequest
     */
    public function createTransaction(CreateTransactionRequest $createTransactionRequest): CreateTransactionResponse
    {
        try {
            return $createTransactionRequest
                ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
                ->withAddedHeader('Content-Type', 'application/json')
                ->sendWith($this);
        } catch (GuzzleException $exception) {
            //do something!
        }
    }

    /**
     * @param CheckTransactionRequest $checkTransactionRequest
     */
    public function checkTransaction(CheckTransactionRequest $checkTransactionRequest): CheckTransactionResponse
    {
        try {
            return $checkTransactionRequest
                ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
                ->withAddedHeader('Content-Type', 'application/json')
                ->sendWith($this);
        } catch (GuzzleException $exception) {
            //do something!
            var_dump($exception->getMessage());die;
        }
    }
}
