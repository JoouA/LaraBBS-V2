<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use GuzzleHttp\Exception\ClientException;
use Overtrue\EasySms\EasySms;
use Cache;

class VerificationCodesController extends Controller
{
    /**
     *
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {

        // 先验证图片验证码，再发送

        $captchaData = Cache::get($request->captcha_key);

        if (! $captchaData){
            return $this->response->error('图片验证码失效',422);
        }

        if (!hash_equals($captchaData['code'],$request->captcha_code)){
            // 图片验证码错误就清除缓存
            Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }


        $mobile = $captchaData['mobile'];

        if (! app()->environment('production')){
            $code = '1234';
        }else{
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($mobile,[
                    'content' => "【larabbs社区】您的验证码是{$code}.如非本人操作，请忽略本短信",
                ]);
            } catch (ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
            }

        }

        $key = 'verificationCode_' . str_random(15);

        $expireAt = now()->addMinutes(10);

        // 缓存短信验证码 10分钟过期。
        Cache::put($key,['mobile' => $mobile,'code' => $code],$expireAt);

        // 清除图片验证码缓存
        Cache::forget($request->captcha_key);

        // 返回图片验证码的发送结果
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
