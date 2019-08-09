<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use Digiwallet\Packages\Transaction\Client\ClientInterface as TransactionClient;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponseInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Interface TransactionRequest
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
interface CheckTransactionInterface extends RequestInterface
{
    /**
     * @return CheckTransactionInterface
     */
    public function enableTestMode(): self;

    /**
     * @return CheckTransactionInterface
     */
    public function withOutlet(int $outletId): self;

    /**
     * @return CheckTransactionInterface
     */
    public function withTransactionId(int $transactionId): self;

    /**
     * @param TransactionClient $client
     * @return CheckTransactionResponseInterface
     */
    public function sendWith(TransactionClient $client): CheckTransactionResponseInterface;
}
