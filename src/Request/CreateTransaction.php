<?php
namespace Digiwallet\Packages\Transaction\Client\Request;

use GuzzleHttp\Psr7\Request;
use Digiwallet\Packages\Transaction\Client\InvoiceLine\InvoiceLineInterface as InvoiceLine;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class CreateTransaction
 * @package Digiwallet\Packages\Transaction\Client\Request
 */
class CreateTransaction extends Request implements CreateTransactionInterface
{
    private const DIGIWALLET_PAY_CREATE_TRANSACTION_URL = 'https://api.digiwallet.nl/unified/transaction';
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
    private $body = [];

    /**
     * @var array
     */
    private $options = [
        'outletID' => null,
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

        'enabledAfterPay' => false,
        'enabledCreditCard' => false,
        'enabledIdeal' => false,
        'enabledBancontact' => false,
        'enabledPaysafeCard' => false,
        'enabledPayPal' => false,
        'enabledSofort' => false,

        'test' => 0,
        'acquirerPreprodTest' => 0
    ];

    /**
     * @var array
     */
    private $invoiceLines = [];

    /**
     * CreateTransaction constructor.
     * @param string $bearer
     * @param int $outletId
     * @param array $options
     */
    public function __construct(string $bearer, int $outletId, array $options = [])
    {
        $this->options['outletID'] = $outletId;

        $this->withOptions($options);

        parent::__construct(
            self::DIGIWALLET_PAY_CREATE_TRANSACTION_HTTP_METHOD,
            self::DIGIWALLET_PAY_CREATE_TRANSACTION_URL,
            [
                'Authorization' => 'Bearer ' . $bearer,
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @param InvoiceLine $invoiceLine
     * @return CreateTransaction
     */
    public function withInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if (!in_array($invoiceLine, $this->invoiceLines, true)) {
            $this->invoiceLines[] = $invoiceLine;
        }

        return $this;
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
     */
    public function withDescription(string $description): void
    {
        $this->options['description'] = $description;
        $this->updateBody();
    }

    /**
     * @param string $suggestedLanguage
     */
    public function withSuggestedLanguage(string $suggestedLanguage): void
    {
        $this->options['suggestedLanguage'] = $suggestedLanguage;
        $this->updateBody();
    }

    /**
     * @param string $consumerEmail
     */
    public function withConsumerEmail(string $consumerEmail): void
    {
        $this->options['consumerEmail'] = $consumerEmail;
        $this->updateBody();
    }

    /**
     * @param string $consumerIp
     */
    public function withConsumerIp(string $consumerIp): void
    {
        $this->options['consumerIP'] = $consumerIp;
        $this->updateBody();
    }

    /**
     * @param string $reportUrl
     */
    public function withReportUrl(string $reportUrl): void
    {
        $this->options['reportURL'] = $reportUrl;
        $this->updateBody();
    }

    /**
     * @param string $cancelURL
     */
    public function withCancelUrl(string $cancelURL): void
    {
        $this->options['cancelURL'] = $cancelURL;
        $this->updateBody();
    }

    /**
     * @param string $currencyCode
     */
    public function withCurrencyCode(string $currencyCode): void
    {
        $this->options['currencyCode'] = $currencyCode;
        $this->updateBody();
    }

    /**
     * @param int $amount
     */
    public function withAmount(int $amount): void
    {
        $this->options['amountChangeable'] = false;
        $this->options['inputAmount'] = $amount;
        $this->options['inputAmountMin'] = null;
        $this->options['inputAmountMax'] = null;
        $this->updateBody();
    }

    /**
     * @param int $min
     * @param int $max
     */
    public function withAmountChangeable(int $min, int $max): void
    {
        $this->options['amountChangeable'] = false;
        $this->options['inputAmount'] = null;
        $this->options['inputAmountMin'] = $min;
        $this->options['inputAmountMax'] = $max;
        $this->updateBody();
    }

    /**
     * @param int $productTypeId
     * @return CreateTransaction
     */
    public function withSofortProductTypeId(int $productTypeId): self
    {
        $this->options['sofortProductTypeId'] = $productTypeId;
    }

    private function updateBody(): void
    {
        $body = $this->buildBody();
        $stream = stream_for($body);
        $this->withBody($stream);
    }

    /**
     * @return array
     */
    private function buildBody(): array
    {
        $body = [
            'outletID' => $this->options['currencyCode'],
            'currencyCode' => $this->options['currencyCode'],
            'description' => $this->options['description'],
            'returnURL' => $this->options['returnURL'],
            'suggestedLanguage' => $this->options['suggestedLanguage'],
            'paymentOptions' => [
                'amountChangeable' => $this->options['amountChangeable'],
                'paymentMethods' => []
            ],
        ];

        if ($this->options['consumerEmail'] !== null) {
            $body['consumerEmail'] = $this->options['consumerEmail'];
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
            $body['inputAmount'] = $this->options['inputAmount'];
        }

        if (!$this->options['amountChangeable']) {
            $body['inputAmountMin'] = $this->options['inputAmountMin'];
            $body['inputAmountMax'] = $this->options['inputAmountMax'];
        }

        if ($this->options['sofortProductTypeId'] !== null) {
            $body['sofortProductTypeID'] = $this->options['sofortProductTypeId'];
        }

        if ($this->options['enabledAfterPay'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_AFTERPAY;
        }

        if ($this->options['enabledCreditCard'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_CREDITCARD;
        }

        if ($this->options['enabledIdeal'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_IDEAL;
        }

        if ($this->options['enabledBancontact'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_BANCONTACT;
        }

        if ($this->options['enabledPaysafeCard'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_PAYSAFECARD;
        }

        if ($this->options['enabledPayPal'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_PAYPAL;
        }

        if ($this->options['enabledSofort'] === true) {
            $body['paymentOptions']['paymentMethods'][] = self::METHOD_SOFORT;
        }

        if (!empty($this->invoiceLines)) {
            $body['afterpayInvoiceLines'] = $this->invoiceLines;
        }

        return $body;
    }

    /**
     * @return bool
     */
    public function validateRequest(): bool
    {
        switch (true) {
            case empty($this->options['description']):
            case $this->options['outletID'] < 1:
            case empty($this->options['returnURL']) || !filter_var($this->options['returnURL'], FILTER_VALIDATE_URL):
            case empty($this->options['inputAmount']) && (empty($this->options['inputAmountMin']) && empty($this->options['inputAmountMax'])):
                return false;
        }

        return true;
    }
}
