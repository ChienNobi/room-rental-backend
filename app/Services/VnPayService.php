<?php
namespace App\Services;


use App\Constants\VnPay;
use Illuminate\Support\Str;

class VnPayService
{
    public function buildRedirectUrl($amount, $locale = 'vn'): string
    {
        $hashSecret = config('services.vn_pay.hash_secret');
        $paymentUrl = config('services.vn_pay.url');

        $data = [
            'vnp_Version' => config('services.vn_pay.version'),
            'vnp_Command' => VnPay::COMMAND['PAY'],
            'vnp_TmnCode' => config('services.vn_pay.tmn_code'),
            'vnp_Locale' => $locale,
            'vnp_CurrCode' => VnPay::CURRENCY['VND'],
            'vnp_TxnRef' => (string)Str::uuid(),
            'vnp_OrderInfo' => 'Nạp tiền vào tài khoản: ' . auth()->user()->name,
            'vnp_OrderType' => VnPay::ORDER_TYPE['OTHER'],
            'vnp_Amount' => $amount * 100,
            'vnp_ReturnUrl' => config('services.vn_pay.return_url'),
            'vnp_IpAddr' => request()->ip(),
            'vnp_CreateDate' => date('YmdHis'),
        ];

        ksort($data);

        $query = http_build_query($data);
        $url = $paymentUrl . '?' . $query;

        $vnpSecureHash = hash_hmac('sha512', $query, $hashSecret);
        $url .= '&vnp_SecureHash=' . $vnpSecureHash;

        return $url;
    }
}
