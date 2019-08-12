<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use Digiwallet\Packages\Transaction\Client\ClientInterface as TransactionClient;
use Digiwallet\Packages\Transaction\Client\InvoiceLine\InvoiceLineInterface as InvoiceLine;
use Digiwallet\Packages\Transaction\Client\Response\CreateTransaction as CreateTransactionResponse;
use Digiwallet\Packages\Transaction\Client\Response\CreateTransactionInterface as CreateTransactionResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class CreateTransaction
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
class CreateTransaction extends Request implements CreateTransactionInterface
{
    private const DIGIWALLET_PAY_CREATE_TRANSACTION_PATH = '/unified/transaction';
    private const DIGIWALLET_PAY_CREATE_TRANSACTION_HTTP_METHOD = 'POST';

    private const PAYMENT_METHODS = [
        self::METHOD_AFTERPAY,
        self::METHOD_CREDITCARD,
        self::METHOD_IDEAL,
        self::METHOD_BANCONTACT,
        self::METHOD_PAYSAFECARD,
        self::METHOD_PAYPAL,
        self::METHOD_SOFORT,
    ];

    public const METHOD_AFTERPAY = 'AFP';
    public const METHOD_CREDITCARD = 'CRC';
    public const METHOD_IDEAL = 'IDE';
    public const METHOD_BANCONTACT = 'MRC';
    public const METHOD_PAYSAFECARD = 'PSC';
    public const METHOD_PAYPAL = 'PYP';
    public const METHOD_SOFORT = 'SOF';

    /**
     * @var array
     */
    private $options = [
        'outletId' => null,
        'currencyCode' => 'EUR',
        'consumerEmail' => null,
        'description' => null,
        'returnUrl' => null,
        'reportUrl' => null,
        'cancelUrl' => null,
        'consumerIp' => null,
        'suggestedLanguage' => null,
        'sofortProductTypeId' => null,
        'amountChangeable' => false,
        'inputAmount' => null,
        'inputAmountMin' => null,
        'inputAmountMax' => null,
        'paymentMethods' => [],
        'test' => 0,
        'acquirerPreprodTest' => 0
    ];

    /**
     * @var array
     */
    private $invoiceLines = [];

    /**
     * CreateTransaction constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(
            self::DIGIWALLET_PAY_CREATE_TRANSACTION_HTTP_METHOD,
            self::DIGIWALLET_PAY_CREATE_TRANSACTION_PATH
        );

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

    /**
     * @param string $description
     * @return CreateTransactionInterface
     */
    public function withDescription(string $description): CreateTransactionInterface
    {
        $this->options['description'] = $description;
        return $this;
    }

    /**
     * @param string $preferredLanguage
     * @return CreateTransactionInterface
     */
    public function withLanguagePreference(string $preferredLanguage): CreateTransactionInterface
    {
        $this->options['suggestedLanguage'] = $preferredLanguage;
        return $this;
    }

    /**
     * @param string $consumerEmail
     * @return CreateTransactionInterface
     */
    public function withConsumerEmail(string $consumerEmail): CreateTransactionInterface
    {
        $this->options['consumerEmail'] = $consumerEmail;
        return $this;
    }

    /**
     * @param string $consumerIp
     * @return CreateTransactionInterface
     */
    public function withConsumerIp(string $consumerIp): CreateTransactionInterface
    {
        $this->options['consumerIp'] = $consumerIp;
        return $this;
    }

    /**
     * @param string $reportUrl
     * @return CreateTransactionInterface
     */
    public function withReportUrl(string $reportUrl): CreateTransactionInterface
    {
        $this->options['reportUrl'] = $reportUrl;
        return $this;
    }

    /**
     * @param string $cancelURL
     * @return CreateTransactionInterface
     */
    public function withCancelUrl(string $cancelURL): CreateTransactionInterface
    {
        $this->options['cancelUrl'] = $cancelURL;
        return $this;
    }

    /**
     * @param string $currencyCode
     * @return CreateTransactionInterface
     */
    public function withCurrency(string $currencyCode): CreateTransactionInterface
    {
        $this->options['currencyCode'] = $currencyCode;
        return $this;
    }

    /**
     * in case of transaction with variable amount, specify both $amount and $maxAmount. This excludes Afterpay.
     * Both amounts should be in cents so for 1 euro you should enter 100
     * @param int $amount
     * @param int|null $maxAmount
     * @return CreateTransactionInterface
     */
    public function withAmount(int $amount, int $maxAmount = null): CreateTransactionInterface
    {
        $this->options['inputAmount'] = $amount;
        $this->options['inputAmountMin'] = $amount;
        $this->options['inputAmountMax'] = $maxAmount;
        $this->options['amountChangeable'] = $maxAmount !== null;
        return $this;
    }

    /**
     * @param int $productTypeId
     * @return CreateTransaction
     */
    public function withProductType(int $productTypeId): CreateTransactionInterface
    {
        $this->options['enabledSofort'] = true;
        $this->options['sofortProductTypeId'] = $productTypeId;
        return $this;
    }

    /**
     * @param InvoiceLine[]|array $invoiceLines
     * @return CreateTransactionInterface
     */
    public function withInvoiceLines(array $invoiceLines): CreateTransactionInterface
    {
        foreach ($invoiceLines as $invoiceLine) {
            $this->withInvoiceLine($invoiceLine);
        }

        return $this;
    }

    /**
     * @param InvoiceLine $invoiceLine
     */
    private function withInvoiceLine(InvoiceLine $invoiceLine): void
    {
        if (!in_array($invoiceLine, $this->invoiceLines, true)) {
            $this->invoiceLines[] = $invoiceLine;
        }
    }

    /**
     * @param string[]|iterable $paymentMethods
     * @return CreateTransactionInterface
     */
    public function withPaymentMethods(iterable $paymentMethods): CreateTransactionInterface
    {
        $this->options['paymentMethods'] = [];
        foreach ($paymentMethods as $paymentMethod) {
            $this->withPaymentMethod($paymentMethod);
        }

        return $this;
    }

    /**
     * @param string $paymentMethod
     */
    private function withPaymentMethod(string $paymentMethod): void
    {
        $exists = in_array($paymentMethod, self::PAYMENT_METHODS, true);
        $notAdded = !in_array($paymentMethod, $this->options['paymentMethods'], true);
        if ($exists && $notAdded) {
            $this->options['paymentMethods'][] = $paymentMethod;
        }
    }

    /**
     * @param int $outletId
     * @return CreateTransactionInterface
     */
    public function withOutlet(int $outletId): CreateTransactionInterface
    {
        $this->options['outletId'] = $outletId;
        return $this;
    }

    /**
     * @param string $returnUrl
     * @return CreateTransactionInterface
     */
    public function withReturnUrl(string $returnUrl): CreateTransactionInterface
    {
        $this->options['returnUrl'] = $returnUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function validateRequest(): bool
    {
        switch (true) {
            case empty($this->options['description']):
            case $this->options['outletId'] < 1:
            case empty($this->options['returnUrl']) || !filter_var($this->options['returnUrl'], FILTER_VALIDATE_URL):
            case empty($this->options['inputAmount']) && (empty($this->options['inputAmountMin']) && empty($this->options['inputAmountMax'])):
                return false;
        }

        if ($this->options['enabledSofort'] && empty($this->options['sofortProductTypeId'])) {
            return false;
        }

        if ($this->options['enabledAfterPay'] && (empty($this->invoiceLines) || $this->options['amountChangeable'])) {
            return false;
        }

        return true;
    }

    /**
     * @return CreateTransactionInterface
     */
    private function buildBody(): CreateTransactionInterface
    {
        $body = [
            'outletID' => $this->options['outletId'],
            'currencyCode' => $this->options['currencyCode'],
            'description' => $this->options['description'],
            'returnURL' => $this->options['returnUrl'],
            'paymentOptions' => [
                'amountChangeable' => $this->options['amountChangeable']
            ]
        ];

        if ($this->options['consumerEmail'] !== null) {
            $body['consumerEmail'] = $this->options['consumerEmail'];
        }

        if ($this->options['suggestedLanguage'] !== null) {
            $body['suggestedLanguage'] = $this->options['suggestedLanguage'];
        }

        if ($this->options['reportUrl'] !== null) {
            $body['reportURL'] = $this->options['reportUrl'];
        }

        if ($this->options['cancelUrl'] !== null) {
            $body['cancelURL'] = $this->options['cancelUrl'];
        }

        if ($this->options['consumerIp'] !== null) {
            $body['consumerIP'] = $this->options['consumerIp'];
        }

        if ($this->options['amountChangeable']) {
            $body['paymentOptions']['inputAmountMin'] = $this->options['inputAmountMin'];
            $body['paymentOptions']['inputAmountMax'] = $this->options['inputAmountMax'];
        }

        if (!$this->options['amountChangeable']) {
            $body['paymentOptions']['inputAmount'] = $this->options['inputAmount'];
        }

        if (!empty($this->options['paymentMethods'])) {
            $body['paymentOptions']['paymentMethods'] = $this->options['paymentMethods'];
        }

        if ($this->options['sofortProductTypeId'] !== null) {
            $body['sofortProductTypeID'] = $this->options['sofortProductTypeId'];
        }

        if (!empty($this->invoiceLines)) {
            $body['afterpayInvoiceLines'] = $this->invoiceLines;
        }

        $stream = stream_for(json_encode($body));

        return $this->withBody($stream);
    }

    /**
     * @param TransactionClient $client
     * @return CreateTransactionResponseInterface
     * @throws GuzzleException
     */
    public function sendWith(TransactionClient $client): CreateTransactionResponseInterface
    {
        $request = $this->buildBody();
        $response = $client->send($request);

        return new CreateTransactionResponse($response);
    }
}
