<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Payaza;

use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\Payaza\Responses\PayoutStatusResponse;
use BrokeYourBike\Payaza\Responses\PayoutResponse;
use BrokeYourBike\Payaza\Interfaces\TransactionInterface;
use BrokeYourBike\Payaza\Interfaces\ConfigInterface;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function payout(TransactionInterface $transaction): PayoutResponse
    {
        $tokenEncoded = base64_encode($this->config->getToken());

        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Payaza {$tokenEncoded}",
                'X-TenantID' => $this->config->getTenantId(),
            ],
            \GuzzleHttp\RequestOptions::JSON => [
                'transaction_type' => 'nuban',
                'service_payload' => [
                    'payout_amount' => $transaction->getAmount(),
                    'transaction_pin' => $this->config->getTransactionPin(),
                    'account_reference' => $this->config->getAccountReference(),
                    'payout_beneficiaries' => [
                        [
                            'credit_amount' => $transaction->getAmount(),
                            'account_number' => $transaction->getBankAccount(),
                            'account_name' => $transaction->getRecipientName(),
                            'bank_code' => $transaction->getBankCode(),
                            'narration' => $transaction->getReference(),
                            'transaction_reference' => $transaction->getReference(),
                            'sender' => [
                                'sender_name' => $this->config->getSenderName(),
                                'sender_phone_number' => $this->config->getSenderPhone(),
                                'sender_address' => $this->config->getSenderAddress(),
                            ],
                        ]
                    ],
                ],
            ],
        ];

        if ($transaction instanceof SourceModelInterface){
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $transaction;
        }

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), "payout-receptor/payout");
        $response = $this->httpClient->request(HttpMethodEnum::POST->value, $uri, $options);
        return new PayoutResponse($response);
    }

    public function payoutStatus(string $reference): PayoutStatusResponse
    {
        $tokenEncoded = base64_encode($this->config->getToken());

        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Payaza {$tokenEncoded}",
                'X-TenantID' => $this->config->getTenantId(),
            ],
        ];

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), "payaza-account/api/v1/mainaccounts/merchant/transaction/{$reference}");
        $response = $this->httpClient->request(HttpMethodEnum::GET->value, $uri, $options);
        return new PayoutStatusResponse($response);
    }
}
