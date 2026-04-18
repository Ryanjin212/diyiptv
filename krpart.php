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
