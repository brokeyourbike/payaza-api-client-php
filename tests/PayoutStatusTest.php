<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Payaza\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Payaza\Responses\PayoutStatusResponse;
use BrokeYourBike\Payaza\Interfaces\ConfigInterface;
use BrokeYourBike\Payaza\Enums\TransactionStatusEnum;
use BrokeYourBike\Payaza\Enums\ErrorCodeEnum;
use BrokeYourBike\Payaza\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class PayoutStatusTest extends TestCase
{
    /** @test */
    public function it_can_fetch_status(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getToken')->willReturn('token');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "message": "Transaction fetched",
                "status": true,
                "data": {
                    "transactionDateTime": "2023-11-17T15:57:59.667599",
                    "transactionReference": "REF1234",
                    "creditAccount": "782634976",
                    "bankCode": "000015",
                    "beneficiaryName": "John Doe",
                    "transactionAmount": 100.00,
                    "fee": 30.00,
                    "sessionId": "1278948127348172340891237401892374",
                    "transactionStatus": "ESCROW_SUCCESS",
                    "narration": "REF1234",
                    "transactionType": "DEBIT",
                    "responseMessage": "Approved or Completely Successful",
                    "responseCode": "00",
                    "currency": "NGN",
                    "balanceBefore": 500.00,
                    "balanceAfter": 370.00
                }
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->payoutStatus('REF1234');
        $this->assertInstanceOf(PayoutStatusResponse::class, $requestResult);
        $this->assertEquals(ErrorCodeEnum::OK->value, $requestResult->response_code);
        $this->assertEquals(TransactionStatusEnum::ESCROW_SUCCESS->value, $requestResult->transaction_status);
    }
}