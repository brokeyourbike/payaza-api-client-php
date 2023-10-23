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
    // transaction has been received and queued for processing
    case TRANSACTION_INITIATED = 'TRANSACTION_INITIATED';

    // amount has been deducted, 
    // but it is being processed by the bank, 
    // and it can be reversed due to network problems
    case ESCROW_SUCCESS = 'ESCROW_SUCCESS';

    // transaction is successful
    case NIP_SUCCESS = 'NIP_SUCCESS';

    // transaction has failed
    case NIP_FAILURE = 'NIP_FAILURE';

    // transaction is still in progress
    case NIP_PENDING = 'NIP_PENDING';

    // transaction fails at the bank, 
    // it is automatically reversed and credited back to your account
    case NIP_REVERSAL_SUCCESS = 'NIP_REVERSAL_SUCCESS';
}
