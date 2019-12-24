# swoole-alidayu

基于阿里云短信sdk修改，使用swoole扩展，client由Coroutine\Http\Client接管。  
v1.0.0  
*1，仅支持单挑发送，批量发送待更新

`
$config = [
    'accessKeyId' => $this->config->alidayu->accessKeyId,
    'accessKeySecret' => $this->config->alidayu->accessKeySecret,
];

$sendSmd = new \Aliyun\SendSms($config);
$code = mt_rand(100000, 999999);
$res = $sendSmd->sendSms('13000000000', '某某机构', 'SMS_12345678', $code);
`
