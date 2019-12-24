<?php

namespace Aliyun;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

/**
 * Class SendSms
 *
 * 这是发送到短信的应用程序
 */

class SendSmd
{
    protected $accessKeyId;
    protected $accessKeySecret;
    // 暂时不支持多Region
    protected $region = "cn-hangzhou";
    // 服务结点
    protected $endPointName = "cn-hangzhou";
    protected $domain = "dysmsapi.aliyuncs.com";
    protected $acsClient = null;



    public function __construct($config)
    {
        // 加载区域结点配置
        Config::load();
        $this->accessKeyId = $config['accessKeyId'];
        $this->accessKeySecret = $config['accessKeySecret'];
        if (isset($config['region'])) {
            $this->region = $config['region'];
        }
        if (isset($config['endPointName'])) {
            $this->endPointName = $config['endPointName'];
        }
        if (isset($config['domain'])) {
            $this->domain;
        }
    }
    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    protected function getAcsClient()
    {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = $this->domain;

        $accessKeyId = $this->accessKeyId; // AccessKeyId

        $accessKeySecret = $this->accessKeySecret; // AccessKeySecret

        // 暂时不支持多Region
        $region = $this->region;

        // 服务结点
        $endPointName = $this->endPointName;


        if ($this->acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            $this->acsClient = new DefaultAcsClient($profile);
        }
        return $this->acsClient;
    }
    /**
     * 发送短信
     * @return stdClass
     */
    public function sendSms($mobile, $signName, $templateCode, $code=null)
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
       
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code" => $code,
            "product" => "dsd"
        ), JSON_UNESCAPED_UNICODE));

        $request->setVersion('2017-05-25');

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }
}
