<?php
// 设置响应头为 M3U 播放列表格式
header('Content-Type: audio/x-mpegurl');
header('Content-Disposition: attachment; filename="live.txt"');



$txturl=trim($_GET['url']);

$ch = curl_init("$txturl");
// 网络请求获取js文件内容，禁用缓存

$headers = array(
    'Cache-Control: no-cache',
    'Pragma: no-cache'
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($ch);
curl_close($ch);
$lines = explode("\n", $content);


$sortlist = curl_init("https://raw.githubusercontent.com/Ryanjin212/diyiptv/refs/heads/main/sortlist.txt");

curl_setopt($sortlist, CURLOPT_HTTPHEADER, $headers);
curl_setopt($sortlist, CURLOPT_RETURNTRANSFER, 1);
$sortlistcontent = curl_exec($sortlist);
curl_close($sortlist);
$sortlistlines = explode("\n", $sortlistcontent);






// 4. 解析数据并转换为 M3U 格式
foreach ($lines as $line) {
    $line = trim($line);
	
    if (empty($line)) continue;

    // 假设你的 PHP 源码输出格式为：频道名称,播放链接
    if (strpos($line, ',') !== false) {
		
        list($info, $name) = explode(',', $line, 2);

$namekey=",".trim($name).",";
foreach ($sortlistlines as $line2) {
    if (str_contains($line2, $namekey)) {   // 行里包含 ",频道名,"

		echo "\n-----------".$line2."---------\n";
$namekey=",".trim($name).",";
		echo "\n-----------".$namekey."---------\n";
										 
        list($name, $id, $png) = explode('@', $line2, 4);
        break;   // 找到就停
    }
	else
	{
		 $id=""; $png="";
	}
}


   


		
if (preg_match('/group-title="([^"]*)"/', $info, $genreinfo)) {

 $genre=$genreinfo[1] ;  // 输出：genre
}
else{ $genre="";}
	

		

   
 
	echo "#EXTINF:-1 tvg-id=\"$id\" tvg-logo=\"$png\" group-title=\"$genre\" ," . trim($name). "\n";
	
}
	else{ echo $line . "\n";}
		
}
?>
