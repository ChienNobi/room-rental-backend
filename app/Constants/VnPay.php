<?php


namespace App\Constants;


class VnPay
{
    const COMMAND = [
        'PAY' => 'pay',
    ];

    const CURRENCY = [
        'VND' => 'VND',
    ];

    const ORDER_TYPE = [
        'BILL_PAYMENT' => 'billpayment',
        'OTHER' => 'other',
    ];
}
