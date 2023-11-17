<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Payaza\Interfaces;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface ConfigInterface
{
    public function getUrl(): string;
    public function getToken(): string;
    public function getTenantId(): string;
    public function getTransactionPin(): string;
    public function getAccountReference(): string;
    public function getSenderName(): string;
    public function getSenderPhone(): string;
    public function getSenderAddress(): string;
}
