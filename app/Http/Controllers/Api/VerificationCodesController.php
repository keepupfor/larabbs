<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);
        if (!$captchaData) {
            return $this->response->error('图形验证码已失效', 422);
        }
        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            \Cache::forget($request->captcha_key);
           return $this->response->errorUnauthorized('图形验证码错误');
        }
        $phone = $request->phone;
        if (!app()->environment('production')) {
            $code = '1234';
        } else {


            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($phone, [
                    'content' => "【Larbbs社区】您的验证码是{$code}。如非本人操作，请忽略本短信"
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg']??'短信发送异常');
            }
        }
        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinute(10);
        //缓存验证码
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        \Cache::forget($request->captcha_key);
        return $this->response->array(['key' => $key,
            'expiredAt' => $expiredAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
