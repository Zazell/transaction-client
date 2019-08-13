<?php
namespace Digiwallet\Packages\Transaction\Client\Response;

/**
 * Interface TransactionResponse
 * @package Digiwallet\Packages\Transaction\Client\Response
 */
interface CheckTransactionInterface
{
    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return string
     */
    public function getTransactionStatus(): string;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string|null
     */
    public function consumerName(): ?string;

    /**
     * @return string|null
     */
    public function consumerIBAN(): ?string;

    /**
     * @return string|null
     */
    public function consumerCountryCode(): ?string;

    /**
     * @return string|null
     */
    public function consumerCardNumberMasked(): ?string;

    /**
     * @return string|null
     */
    public function consumerCardExpiryDate(): ?string;

    /**
     * @return string|null
     */
    public function consumerCardBrand(): ?string;
}
