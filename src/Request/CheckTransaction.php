<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use Digiwallet\Packages\Transaction\Client\ClientInterface as TransactionClient;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransaction as CheckTransactionResponse;
use Digiwallet\Packages\Transaction\Client\Response\CheckTransactionInterface as CheckTransactionResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

/**
 * Class CheckTransaction
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
class CheckTransaction extends Request implements CheckTransactionInterface
{
    private const DIGIWALLET_PAY_CHECK_TRANSACTION_PATH = '/unified/transaction';
    private const DIGIWALLET_PAY_CHECK_TRANSACTION_HTTP_METHOD = 'GET';

    private $test = 0;
    private $outletId;
    private $transactionId;
    private $bearer;

    /**
     * CheckTransaction constructor.
     */
    public function __construct()
    {
        parent::__construct(self::DIGIWALLET_PAY_CHECK_TRANSACTION_HTTP_METHOD, '');
    }

    /**
     * @return CheckTransactionInterface
     */
    public function enableTestMode(): CheckTransactionInterface
    {
        $this->test = 1;
        return $this;
    }

    /**
     * @param int $outletId
     * @return CheckTransactionInterface
     */
    public function withOutlet(int $outletId): CheckTransactionInterface
    {
        $this->outletId = $outletId;
        return $this;
    }

    /**
     * @param string $bearer
     * @return CheckTransactionInterface
     */
    public function withBearer(string $bearer): CheckTransactionInterface
    {
        $this->bearer = $bearer;
        return $this;
    }

    /**
     * @param int $transactionId
     * @return CheckTransactionInterface
     */
    public function withTransactionId(int $transactionId): CheckTransactionInterface
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * @param TransactionClient $client
     * @return CheckTransactionResponseInterface
     * @throws GuzzleException
     */
    public function sendWith(TransactionClient $client): CheckTransactionResponseInterface
    {
        $uri = $this->getUri();
        $uri->withPath(self::DIGIWALLET_PAY_CHECK_TRANSACTION_PATH . implode('/', [$this->transactionId, $this->outletId, $this->test]));

        $request = $this
            ->withUri($uri)
            ->withAddedHeader('Authorization', 'Bearer ' . $this->bearer)
            ->withAddedHeader('Content-Type', 'application/json');

        $response = $client->checkTransaction($request);

        return new CheckTransactionResponse($response);
    }
}
