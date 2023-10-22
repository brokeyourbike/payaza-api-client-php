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
                "response_code": 200,
                "response_message": "Request successful.",
                "response_content": {
                    "transaction_status": "ESCROW_SUCCESS",
                    "transaction_status_description": null,
                    "transaction_reference": "REF1234",
                    "narration": "Payout",
                    "session_id": null,
                    "transaction_date": "2023-10-13T16:33:37.838328",
                    "to_account": "0690767319",
                    "account_name": "John Doe",
                    "transaction_amount": "100.00",
                    "fee_amount": "0.00"
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
        $this->assertEquals(ErrorCodeEnum::SUCCESSFUL->value, $requestResult->response_code);
        $this->assertEquals(TransactionStatusEnum::ESCROW_SUCCESS->value, $requestResult->transaction_status);
    }
}