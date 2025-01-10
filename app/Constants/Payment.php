<?php


namespace App\Constants;


class Payment
{
    const COMMAND = [
        'PAY' => 'pay',
    ];

    const CURRENCY = [
        'VND' => 'VND',
    ];

    const LOCALE = [
        'VN' => 'vn',
        'EN' => 'en',
    ];

    const ORDER_TYPE = [
        'BILL_PAYMENT' => 'billpayment',
        'OTHER' => 'other',
    ];

    const PAYMENT_TYPE = [
        'DEPOSIT' => 'deposit',
        'CHARGE' => 'charge',
        'REFUND' => 'refund',
    ];

    const PAYMENT_STATUS = [
        'PENDING' => 'pending',
        'SUCCESS' => 'success',
        'FAILED' => 'failed',
    ];

    const PAYMENT_GATEWAY = [
        'SYSTEM' => 'system',
        'VN_PAY' => 'vn-pay',
        'MOMO' => 'momo',
        'ZALO_PAY' => 'zalo-pay',
        'BANKING' => 'banking',
    ];
}
