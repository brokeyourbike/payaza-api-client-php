<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Payaza\Responses;

use Spatie\DataTransferObject\Attributes\MapFrom;
use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class PayoutStatusResponse extends JsonResponse
{
    #[MapFrom('data.responseCode')]
    public ?string $response_code;

    #[MapFrom('data.responseMessage')]
    public ?string $response_message;
    
    #[MapFrom('data.transactionStatus')]
    public ?string $transaction_status;
}

