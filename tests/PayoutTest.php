<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Payaza\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Payaza\Responses\PayoutResponse;
use BrokeYourBike\Payaza\Interfaces\TransactionInterface;
use BrokeYourBike\Payaza\Interfaces\ConfigInterface;
use BrokeYourBike\Payaza\Enums\TransactionStatusEnum;
use BrokeYourBike\Payaza\Enums\ErrorCodeEnum;
use BrokeYourBike\Payaza\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class PayoutTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getReference')->willReturn('REF123');
        $transaction->method('getBankAccount')->willReturn('12345');
        $transaction->method('getBankCode')->willReturn('bank12345');
        $transaction->method('getRecipientName')->willReturn('John Doe');
        $transaction->method('getAmount')->willReturn(50.00);

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);
        
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getToken')->willReturn('token');
        $mockedConfig->method('getTransactionPin')->willReturn('1234');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "response_code": 200,
                "response_message": "Request successfully  submitted",
                "response_content": {
                    "transaction_status": "09",
                    "narration": "Payout",
                    "transaction_time": "2023-10-13T16:33:37.906672",
                    "amount": 100,
                    "response_status": "TRANSACTION_INITIATED",
                    "response_description": "Transaction has been successfully submitted for processing"
                },
                "resp_code": "09"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->payout($transaction);
        $this->assertInstanceOf(PayoutResponse::class, $requestResult);
        $this->assertEquals(ErrorCodeEnum::SUCCESSFUL->value, $requestResult->response_code);
        $this->assertEquals(TransactionStatusEnum::TRANSACTION_INITIATED->value, $requestResult->transaction_status);
    }

    /** @test */
    public function it_can_handle_failure(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getReference')->willReturn('ref-123');
        $transaction->method('getBankAccount')->willReturn('12345');
        $transaction->method('getBankCode')->willReturn('bank12345');
        $transaction->method('getRecipientName')->willReturn('John Doe');
        $transaction->method('getAmount')->willReturn(50.00);

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);
        
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getToken')->willReturn('token');
        $mockedConfig->method('getTransactionPin')->willReturn('1234');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "response_code": 500,
                "response_message": "Insufficient Balance"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->payout($transaction);
        $this->assertInstanceOf(PayoutResponse::class, $requestResult);
        $this->assertEquals(ErrorCodeEnum::FAILED->value, $requestResult->response_code);
        $this->assertNull($requestResult->transaction_status);
    }
}