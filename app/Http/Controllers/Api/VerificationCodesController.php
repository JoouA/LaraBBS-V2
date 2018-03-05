<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use GuzzleHttp\Exception\ClientException;
use Overtrue\EasySms\EasySms;
use Cache;

class VerificationCodesController extends Controller
{
    /**
     * 发送验证码，就信息存在Cache中
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $mobile = $request->mobile;

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

        Cache::put($key,['mobile' => $mobile,'code' => $code],$expireAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
