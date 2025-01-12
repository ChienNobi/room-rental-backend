<?php
namespace App\Services;


use App\Constants\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VnPayService
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function createPayment($amount, $locale = 'vn'): string
    {
        $description = 'Nạp tiền vào tài khoản: ' . auth()->user()->name;
        $orderId = PaymentTransaction::query()->insertGetId([
            'user_id' => auth()->id(),
            'type' => Payment::PAYMENT_TYPE['DEPOSIT'],
            'amount' => $amount,
            'description' => $description,
            'status' => Payment::PAYMENT_STATUS['PENDING'],
            'gateway' => Payment::PAYMENT_GATEWAY['VN_PAY'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->buildRedirectUrl($amount, $orderId, $description, $locale);
    }



    public function buildRedirectUrl($amount, $orderId, $description, $locale = Payment::LOCALE['VN']): string
    {
        $hashSecret = config('services.vn_pay.hash_secret');
        $paymentUrl = config('services.vn_pay.url');

        $data = [
            'vnp_Version' => config('services.vn_pay.version'),
            'vnp_Command' => Payment::COMMAND['PAY'],
            'vnp_TmnCode' => config('services.vn_pay.tmn_code'),
            'vnp_Locale' => $locale,
            'vnp_CurrCode' => Payment::CURRENCY['VND'],
            'vnp_TxnRef' => $orderId,
            'vnp_OrderInfo' => $description,
            'vnp_OrderType' => Payment::ORDER_TYPE['OTHER'],
            'vnp_Amount' => $amount * 100,
            'vnp_ReturnUrl' => route('vnpay.callback'),
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

    public function vnPayCallback($request): array
    {
        try {
            DB::beginTransaction();
            $vnpSecureHash = $request->get('vnp_SecureHash');
            $data = $request->except('vnp_SecureHash');

            $data = array_filter($data, function ($key) {
                return str_starts_with($key, 'vnp_');
            }, ARRAY_FILTER_USE_KEY);

            ksort($data);

            $query = http_build_query($data);

            if (!$this->verifyHashSecret($query, $vnpSecureHash)) {
                return [
                    'Message' => 'Invalid signature',
                    'RspCode' => '97',
                ];
            }

            $transactionId = $data['vnp_TransactionNo'];
            $amount = $data['vnp_Amount'] / 100;
            $bankCode = $data['vnp_BankCode'];
            $orderId = $data['vnp_TxnRef'];

            $transaction = PaymentTransaction::query()->find($orderId);

            $result = $this->verifyTransaction($transaction, $amount);

            $hasError = $result['Error'];

            $paymentStatus = (!$hasError && $data['vnp_ResponseCode'] == '00' && $data['vnp_TransactionStatus'])
                ? Payment::PAYMENT_STATUS['SUCCESS']
                : Payment::PAYMENT_STATUS['FAILED'];

            $transaction->update([
                'status' => $paymentStatus,
                'reference_id' => $transactionId,
                'bank_code' => $bankCode,
                'payment_result_description' => $result['Message'],
            ]);

            if($hasError) {
                return $result;
            }

            $this->userService->updateUserBalance($transaction->user_id, $amount, Payment::PAYMENT_TYPE['DEPOSIT']);
            DB::commit();
            return [
                'Message' => 'Confirm Success',
                'RspCode' => '00',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('------ Has error when confirm payment: ' . $e->getMessage());
            return [
                'Message' => 'Unknown error',
                'RspCode' => '99',
            ];
        }
    }

    public function verifyTransaction($transaction, $amount): array
    {
        if(!$transaction) {
            return [
                'Error' => true,
                'Message' => 'Order not found',
                'RspCode' => '01',
            ];
        }

        if ($transaction->status !== Payment::PAYMENT_STATUS['PENDING']) {
            return [
                'Error' => true,
                'Message' => 'Order already confirmed',
                'RspCode' => '02',
            ];
        }

        if ($transaction->amount != $amount) {
            return [
                'Error' => true,
                'Message' => 'Invalid amount',
                'RspCode' => '04',
            ];
        }

        return [
            'Error' => false,
            'Message' => 'Confirm Success',
            'RspCode' => '00',
        ];
    }

    public function verifyHashSecret($query, $hash): bool
    {
        $hashSecret = config('services.vn_pay.hash_secret');
        $vnpSecureHashCalculated = hash_hmac('sha512', $query, $hashSecret);

        return $hash === $vnpSecureHashCalculated;
    }
}
