<?php
namespace Digiwallet\Packages\Transaction\Client\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Class CreateTransaction
 * @package Digiwallet\Packages\Transaction\Client\Response
 */
class CreateTransaction implements CreateTransactionInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $transactionId;

    /**
     * @var string
     */
    private $launchUrl;

    /**
     * CreateTransaction constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $this->status = $data['status'];
        $this->message = $data['message'];
        $this->transactionId = $data['transactionID'];
        $this->launchUrl = $data['launchURL'];

        return $this;
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function transactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function launchUrl(): string
    {
        return $this->launchUrl;
    }
}
