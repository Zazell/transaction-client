<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class CreateTransaction
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
class CreateTransaction extends Request implements CreateTransactionInterface
{
    /**
     * @var array
     */
    private $body;

    /**
     * CreateTransaction constructor.
     * @param string $uri
     * @param string $bearer
     * @param int $outletId
     * @param string $description
     * @param string $returnURL
     * @param int $test
     * @param int $acquirerPreProdTest
     */
    public function __construct(
        string $uri,
        string $bearer,
        int $outletId,
        string $description,
        string $returnURL,
        int $test = 0,
        int $acquirerPreProdTest = 0
    ) {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $bearer
        ];

        $this->body = [
            'outletID' => $outletId,
            'description' => $description,
            'returnURL' => $returnURL,
            'paymentOptions' => [],
            'test' => $test,
            'acquirerPreprodTest' => $acquirerPreProdTest
        ];

        parent::__construct('POST', $uri, $headers, $this->body);
    }

    public function blop()
    {
        [

            'consumerEmail' => 'sjonnie@tester.eu',
            'reportURL' => 'https => //www.yoursite.gg/report',
            'cancelURL' => 'https => //www.yoursite.gg/cancel',
            'consumerIP' => '123.123.123.123',
            'suggestedLanguage' => 'NLD',
            'test' => 1,
            'acquirerPreprodTest' => 1,
            'sofortProductTypeID' => 1,
            'afterpayInvoiceLines' => '']
    }

    public function withConsumerEmail(string $consumerEmail): void
    {
        $this->body['consumerEmail'] = $consumerEmail;
        $this->updateBody();
    }

    /**
     * @param string $reportUrl
     */
    public function withReportUrl(string $reportUrl): void
    {
        $this->body['reportURL'] = $reportUrl;
        $this->updateBody();
    }

    /**
     * @param string $currencyCode
     */
    public function withCurrencyCode(string $currencyCode)
    {
        $this->body['currencyCode'] = $currencyCode;
        $this->updateBody();
    }

    private function updateBody(): void
    {
        $stream = stream_for($this->body);
        $this->withBody($stream);
    }
}
