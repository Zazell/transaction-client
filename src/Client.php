<?php
namespace Digiwallet\Packages\Transaction\Client;

use Digiwallet\Packages\Transaction\Client\Request\CheckTransactionInterface as CheckTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Request\CreateTransactionInterface as CreateTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponse;
use Digiwallet\Packages\Transaction\Client\Response\CreateTransactionInterface as CreateTransactionResponse;
use GuzzleHttp\Client as GuzzleClient;

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
     * @param CreateTransactionRequest $request
     * @return CreateTransactionResponse
     */
    public function createTransaction(CreateTransactionRequest $request): CreateTransactionResponse
    {
        return $request
            ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
            ->withAddedHeader('Content-Type', 'application/json')
            ->sendWith($this);
    }

    /**
     * @param CheckTransactionRequest $request
     * @return CheckTransactionResponse
     */
    public function checkTransaction(CheckTransactionRequest $request): CheckTransactionResponse
    {
        return $request
            ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
            ->withAddedHeader('Content-Type', 'application/json')
            ->sendWith($this);
    }
}
