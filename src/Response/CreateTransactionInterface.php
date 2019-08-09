<?php
namespace Digiwallet\Packages\Transaction\Client\Response;

/**
 * Interface TransactionResponse
 * @package Digiwallet\Packages\Transaction\Client\Response
 */
interface CreateTransactionInterface
{
    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return string
     */
    public function message(): string;

    /**
     * @return int
     */
    public function transactionId(): int;

    /**
     * @return string
     */
    public function launchUrl(): string;

}
