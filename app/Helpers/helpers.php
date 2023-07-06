<?php

use App\Enums\ResponseCodeEnum;
use App\Models\Base\Order;
use App\Services\App\Merchant\Activity\ActivityLogger;
use App\Services\Base\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Spatie\Activitylog\ActivityLogStatus;

if (!function_exists("fullUrlOss")) {

    function fullUrlOss(?string $path): string
    {
        if (!$path){
            return '';
        }

        $parseUrl = parse_url($path);
        if (isset($parseUrl['scheme']) && isset($parseUrl['host'])){
            return $path;
        }

        return config('dingdong.scheme.image').'://'.config('dingdong.domain.image').'/'.ltrim($path, '/');
    }
}

if (!function_exists("webDomain")) {

    function webDomain(?string $path): string
    {
        if (!$path){
            return '';
        }

        $parseUrl = parse_url($path);
        if (isset($parseUrl['scheme']) && isset($parseUrl['host'])){
            return $path;
        }

        return 'https://web.ddzuwu.com/'.ltrim($path, '/');
    }
}

if (!function_exists("ossThumbnail")) {

    function ossThumbnail(?string $path): string
    {
        if (!$path){
            return '';
        }

        return fullUrlOss($path) . '?x-oss-process=style/mini-thumbnail';
    }
}

if (!function_exists("hideName")) {

    function hideName(string $name): bool|string
    {
        if (empty($name)){
            return '';
        }

        $nameStrLen = mb_strlen($name, 'utf-8');              // 计算姓名长度
        $name    =    mb_substr($name, -1, 1, 'utf-8');       // 截取姓名最后一位

        return str_repeat("*", $nameStrLen - 1)  . $name;
    }
}

if(!function_exists("hideIdCard")){
    function hideIdCard(string $idNumber): string
    {
        $length = strlen($idNumber);
        $hiddenLength = $length - 4; // 隐藏位数，这里设置为身份证号码长度-4

        // 将身份证号码第2位到倒数第2位之间的字符替换成*号
        $hiddenStr = str_repeat('*', $hiddenLength);
        return substr_replace($idNumber, $hiddenStr, 2, $hiddenLength);
    }
}

if (!function_exists('getVersionIdByVersionCode')) {

    /**
     * 根据版本号获取版本id
     *
     * @param string $versionCode
     * @param int $baseNum
     * @return int
     */
    function getVersionIdByVersionCode(string $versionCode, int $baseNum = 100): int {

        $versions = explode('.', $versionCode);
        $length = count($versions);
        $versionId = 0;

        foreach ($versions as $index=>$version) {
            $versionId += ((int)$version * (int)pow($baseNum, $length - 1 - $index));
        }

        return $versionId;
    }
}


if (!function_exists('log_exception')) {

    /**
     * 记录错误或异常
     *
     * @param string $message
     * @param null|Throwable $t
     * @param array $context
     * @param string|null $channel
     * @param string $logLevel
     * @return void
     */
    function logError(string $message, ?Throwable $t = null, array $context = [], ?string $channel = null, string $logLevel = \Psr\Log\LogLevel::ERROR):void {

        $traceException = [];
        if ($t instanceof Throwable) {
            $traceException = [
                'file' => $t->getFile(),
                'line' => $t->getLine(),
                'message' => $t->getMessage(),
                'code' => $t->getCode(),
            ];

            if ($t instanceof \App\Exceptions\ServiceException) {
                $traceException['data'] = $t->getData();
            }
        }

        if ($traceException) {
            if ($context) {
                $context['exception'] = $traceException;
            } else {
                $context = $traceException;
            }
        }

        Log::channel($channel)->log($logLevel, $message, $context);
    }

}

if (!function_exists('getUniqueSerialNo')) {

    /**
     * 获取唯一序列号 -- 雪花算法
     *
     * @param string|null $prefix
     * @param int|null $startTimestamp
     * @param Closure|\Godruoyi\Snowflake\SequenceResolver|null $sequence
     * @return string
     * @throws Exception
     * @auther sksyer
     * @history
     */
    function getUniqueSerialNo(
        null|string $prefix = null,
        ?int $startTimestamp = null,
        null|Closure|\Godruoyi\Snowflake\SequenceResolver $sequence = null
    ): string
    {

        $snowflake = new \Godruoyi\Snowflake\Snowflake();

        if (!is_null($startTimestamp)) {
            $snowflake->setStartTimeStamp($startTimestamp * 1000);
        }

        if (!is_null($sequence)) {
            $snowflake->setSequenceResolver($sequence);
        }

        $id = $snowflake->id();

        return !is_null($prefix) ? $prefix.$id : $id;
    }

}

if (!function_exists('makeNonceStr')) {

    function makeNonceStr(int $len): string
    {

        $letters = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $lettersLen = count($letters);
        $nonceStr = '';

        for ($i = 0; $i < $len; $i++) {
            $nonceStr .= $letters[mt_rand(0, $lettersLen - 1)];
        }

        return $nonceStr;
    }
}

if (!function_exists('simpleAuthCode')) {


    function simpleAuthCode(string $string, string $key, string $operation = 'DECODE', int $expiry = 0): string
    {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $key = md5($key);

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc . str_replace('=', '', base64_encode($result));
        }

    }
}


if (!function_exists('getUriOss')) {

    function getUriOss(string $fullUrl): string {

        return parse_url($fullUrl, PHP_URL_PATH) ?: '';
    }
}


if (!function_exists('checkIdCard')) {
    function checkIdCard(string $idCard): string|array
    {
        $idCard = strtoupper($idCard);
        if (!preg_match('#^\d{17}(\d|X)$#', $idCard)) {
            return false;
        }
        // 判断出生年月日的合法性(解决号码为666666666666666666也能通过校验的问题)
        $birth = substr($idCard, 6, 8);
        if ($birth < "19000101" || $birth > date("Ymd")) {
            return false;
        }
        $year = substr($birth, 0, 4);
        $month = substr($birth, 4, 2);
        $day = substr($birth, 6, 2);
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        // 校验身份证格式(mod11-2)
        $check_sum = 0;
        for ($i = 0; $i < 17; $i++) {
            // $factor = (1 << (17 - $i)) % 11;
            $check_sum += $idCard[$i] * ((1 << (17 - $i)) % 11);
        }
        $check_code = (12 - $check_sum % 11) % 11;
        $check_code = $check_code == 10 ? 'X' : strval($check_code);
        if ($check_code !== substr($idCard, -1)) {
            return false;
        }
        return true;
    }

}

if (!function_exists('oneClickPullAlipayService')) {
    function oneClickPullAlipayService(string $alipayH5Url): string|array
    {
        if (empty($alipayH5Url)){
            return '';
        }

        $request = request();
        if ($request->header('Platform') == \App\Models\Base\User::PLATFORM_APP_IOS
            || $request->header('Platform') == \App\Models\Base\User::PLATFORM_APP_ANDROID
        ){
            return 'alipays://platformapi/startapp?appId=20000067&url='.rawurlencode($alipayH5Url);
        }

        return $alipayH5Url;
    }

}

if (!function_exists('civetSdkSetsMerchantIdentity')) {
    /**
     * 设置灵猫SDK的商户密钥
     * @param Model $model
     * @return void
     */
    function civetSdkSetsMerchantIdentity(Model $model): void
    {
        if (request()->hasHeader('CivetAPPId')){
            return;
        }

        if ($model instanceof \App\Models\Base\Product
        || $model instanceof \App\Models\Base\ProductSku
        || $model instanceof Order
        || $model instanceof \App\Models\Base\UserAntContractInfo
        ) {
            $merchantId = $model->merchant_id;
            $merchant = \App\Models\Base\Merchant::query()->find($merchantId);
        } elseif ($model instanceof \App\Models\Base\Merchant){
            $merchant = $model;
        } else {
            Log::error('灵猫SDK无法设置商户密钥，原因：Model无法识别', [$model]);
            return;
        }

        if (is_null($merchant) || empty($merchant->civet_app_id)){
            Response::fail('商家配置信息获取失败', ResponseCodeEnum::SERVICE_GLOBAL_FAIL);
            return;
        }
        request()->headers->set('CivetAPPId', $merchant->civet_app_id);
    }
}

if (!function_exists('getSecureOrderSnapshot')) {
    /**
     * (安全的)获取订单快照
     * @param Order $order
     * @param string $key
     * @return mixed
     */
    function getSecureOrderSnapshot(Order $order, string $key): mixed
    {
        /**
         * product_name
         * merchant_logo
         * merchant_name
         * product_sku_cover
         * product_sku_group_value
         */
        return $order->basic_snapshot[$key] ?? '';
    }
}

if (!function_exists('getRealClientIp')) {

    function getRealClientIp(): string {

        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_REAL_IP')) {
            $ip = getenv('HTTP_X_REAL_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            $ips = explode(',', $ip);
            $ip = $ips[0];
        } elseif (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = '0.0.0.0';
        }

        return $ip;

    }
}

if (!function_exists('merchantActivity')) {

    function merchantActivity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}

//图片公有转存到私有
if(!function_exists('imagePublicUnloadingPrivate')){
    function imagePublicUnloadingPrivate(string $image_url = ''): string|bool
    {
        $path = parse_url($image_url)['path'];
        $disk = Storage::disk('oss');
        if (!$disk->exists($path)) {
            return false;
        }
        // 上传到私有数据桶
        $source = $disk->readStream($path);
        // 删除公有oss数据
        $disk->delete($path);
        $file = app(FileService::class)->uploadStreamPrivate($source,pathinfo($path, PATHINFO_EXTENSION), 'identity');
        return $file;
    }
}

/**
 * 判断一个字符串是否包含数组中的某个元素
 */
if(!function_exists('strIsExistsArray')){
    function strIsExistsArray(string $str,array $array): bool
    {
        foreach ($array as $item){
            if (str_contains($str, $item)){
                return true;
            }
        }
        return false;
    }
}
