<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Payaza\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum ErrorCodeEnum: string
{
    case OK = '00';
    case SUCCESSFUL = '200';
    case FAILED = '500';
}
