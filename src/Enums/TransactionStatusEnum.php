<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Payaza\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum TransactionStatusEnum: string
{
    case TRANSACTION_INITIATED = 'TRANSACTION_INITIATED';
    case ESCROW_SUCCESS = 'ESCROW_SUCCESS';
}
