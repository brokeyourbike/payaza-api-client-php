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
    public ?string $response_code;
    public ?string $response_message;
    #[MapFrom('response_content.transaction_status')]
    public ?string $transaction_status;
}

