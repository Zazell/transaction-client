<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use Digiwallet\Packages\Transaction\Client\ClientInterface as TransactionClient;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponseInterface;
use GuzzleHttp\Psr7\Request;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransaction as CheckTransactionResponse;

/**
 * Class CheckTransaction
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
class CheckTransaction extends Request implements CheckTransactionInterface
{
    private const DIGIWALLET_PAY_CHECK_TRANSACTION_PATH = '/unified/transaction/%d/%d';
    private const DIGIWALLET_PAY_CHECK_TRANSACTION_HTTP_METHOD = 'GET';

    private $test;
    private $outletId;
    private $transactionId;

    /**
     * CheckTransaction constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->withOptions($options);

    }

    /**
     * @param array $options
     */
    private function withOptions(array $options): void
    {
        foreach ($options as $variable => $option) {
            $this->withOption($variable, $option);
        }
    }

    /**
     * @param string $variable
     * @param string $value
     */
    private function withOption(string $variable, string $value): void
    {
        if (isset($this->options[$variable]) && $this->options[$variable] !== $value) {
            $this->options[$variable] = $value;
        }
    }

    public function enableTestMode(): CheckTransactionInterface
    {
        return $this;
    }

    public function withOutlet(int $outletId): CheckTransactionInterface
    {
        return $this;
    }

    public function withTransactionId(int $transactionId): CheckTransactionInterface
    {
        return $this;
    }

    public function sendWith(TransactionClient $client): CheckTransactionResponseInterface
    {
        parent::__construct(
            self::DIGIWALLET_PAY_CHECK_TRANSACTION_HTTP_METHOD,
            sprintf(self::DIGIWALLET_PAY_CHECK_TRANSACTION_PATH, $this->outletId, $this->transactionId)
        );
        $response = $client->send($this);

        return new CheckTransactionResponse($response);
    }
}
