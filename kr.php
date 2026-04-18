<?php
error_reporting(0);

// ---------- User-Agent ----------
$ua_value = isset($_GET['ua']) ? $_GET['ua'] : 'okhttp/3.15';
$ua = "User-Agent: $ua_value";

// ---------- Channel ID ----------
$id = trim($_GET['id']);
if ($id === '') {
    http_response_code(400);
    exit;
}

// ---------- Source selection ----------
$sp_array1 = [
    "1","3","11","12","8","13","14","15",
    "24_F1BFA250","31_0B41417D","1_EA49B3B2",
    "11_087155E9","12_7066CF5A","13_B4E209CA",
    "14_ECE1A251","15_6B9253F0","3_2C9275B7"
];
$sp_array2 = ["26","28"];

if (in_array($id, $sp_array1)) {
    $url = "http://www.hwado.net/webtv/public/".$id.".php";
} elseif (in_array($id, $sp_array2)) {
    $url = "http://mytv.dothome.co.kr/ch/catv/".$id.".php";
} else {
    $url = "http://www.hwado.net/webtv/catv/".$id.".php";
}

  
// 将中文域名转换为 Punycode 格式
$punycode_url = idn_to_ascii(parse_url($url, PHP_URL_HOST),IDNA_NONTRANSITIONAL_TO_ASCII,INTL_IDNA_VARIANT_UTS46);
$encoded_url = str_replace(parse_url($url, PHP_URL_HOST), $punycode_url, $url);


/*--以下处理链接重定向打包代码块---*/
function getPageContent(string $url,$ua): ?string
{
    $ch = curl_init(); // 初始化cURL资源

    // 设置cURL选项
    curl_setopt_array($ch, [
        CURLOPT_URL => $url, // 请求的原始URL
        CURLOPT_RETURNTRANSFER => true, // 将响应数据作为字符串返回，而非直接输出
        CURLOPT_FOLLOWLOCATION => true, // 允许cURL跟踪重定向，并自动处理它们
        CURLOPT_MAXREDIRS => 10,  // 设置最大重定向次数限制，避免无限循环
        CURLOPT_CONNECTTIMEOUT => 10, // 设置连接超时时间（可选，根据需要调整）
        CURLOPT_TIMEOUT => 15, // 设置整体请求超时时间（可选，根据需要调整）
        CURLOPT_SSL_VERIFYPEER => false,  // 如果目标URL使用HTTPS且证书验证存在问题，可临时禁用（生产环境中应启用并正确配置）
        CURLOPT_SSL_VERIFYHOST => false, // 同上，生产环境中应设置为2并正确配置

        // 添加User-Agent请求头
        CURLOPT_HTTPHEADER => [
            $ua,
        ],
    ]);
    $content = curl_exec($ch); //执行cURL请求并获取响应内容

    // 检查请求是否成功
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpStatus !== 200) {
        // 如果请求失败或返回非200状态码，返回null
        //$content = null;   
        echo " 原链接打不开，请输入有效链接".$content;
        exit(); //结束运行
    }
    curl_close($ch);// 关闭cURL资源
    return $content;
}


/*---以下调用处理链接重定向代码块--*/
   //请求网页并获取密文，并去除首尾空格
     $response = trim(getPageContent($encoded_url,$ua));
 
     preg_match("/url=(.*?)'/", $response, $matches);
   
     $m3u8_url = $matches[1];
      
// 检查字符串是否以双引号开始
if (strpos($m3u8_url, '"') === 0) {
    // 移除开头的双引号
    $m3u8_url = substr($m3u8_url, 1);

    // 检查字符串是否以双引号结束
    if (substr($m3u8_url, -1) === '"') {
        // 移除结尾的双引号
        $m3u8_url = substr($m3u8_url, 0, -1);
    }
}

// 输出处理后的 URL
     echo $m3u8_url;
     header('Location: '.$m3u8_url);
  
  

?>
