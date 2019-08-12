<?php
namespace Digiwallet\Packages\Transaction\Client;

use Digiwallet\Packages\Transaction\Client\Request\CheckTransactionInterface as CheckTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Request\CreateTransactionInterface as CreateTransactionRequest;
use Digiwallet\Packages\Transaction\Client\Response\CreateTransactionInterface as CreateTransactionResponse;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponse;

/**
 * Interface ClientInterface
 * @package Digiwallet\Packages\Transaction\Client
 */
interface ClientInterface extends \GuzzleHttp\ClientInterface
{
    /**
     * @param CreateTransactionRequest $request
     * @return CreateTransactionResponse
     */
    public function createTransaction(CreateTransactionRequest $request): CreateTransactionResponse;

    /**
     * @param CheckTransactionRequest $request
     * @return CheckTransactionResponse
     */
    public function checkTransaction(CheckTransactionRequest $request): CheckTransactionResponse;
}
