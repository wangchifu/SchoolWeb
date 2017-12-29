<?php
$page=1;
$last_class = "";
$data ="<table style='border: 1px solid #000000;border-collapse: collapse;'><tr style='border: 1px solid #000000;'><td style='border: 1px solid #000000;width:50px'>班級</td><td style='border: 1px solid #000000;width:50px'>座號</td><td style='border: 1px solid #000000;width:80px'>姓名</td><td style='border: 1px solid #000000;width:70px'>請假天數</td><td style='border: 1px solid #000000;width:50px'>退費</td><td style='border: 1px solid #000000;width:120px'>簽名</td><td style='border: 1px solid #000000;'>請假日期</td></tr>";
foreach($abs_data as $k=>$v){
    if($last_class != substr($k,0,3) and !empty($last_class)){
        $data .="</table>頁：".$page."<br>承辦：<p style='page-break-after:always'></p><table style='border: 1px solid #000000;border-collapse: collapse;'><tr style='border: 1px solid #000000;'><td style='border: 1px solid #000000;width:50px'>班級</td><td style='border: 1px solid #000000;width:50px'>座號</td><td style='border: 1px solid #000000;width:80px'>姓名</td><td style='border: 1px solid #000000;width:70px'>請假天數</td><td style='border: 1px solid #000000;width:50px'>退費</td><td style='border: 1px solid #000000;width:120px'>簽名</td><td style='border: 1px solid #000000;'>請假日期</td></tr>";
        $data .="<tr style='border: 1px solid #000000;'><td style='border: 1px solid #000000;'>".substr($k,0,3)."</td><td style='border: 1px solid #000000;'>".substr($k,3,2)."</td><td style='border: 1px solid #000000;font-size:20px;'>".$v['name']."</td><td style='border: 1px solid #000000;'>".$v['times']."</td><td>".$v['back_money']."</td><td style='border: 1px solid #000000;'>　　　　　　</td><td style='border: 1px solid #000000;'><font size=1>".$v['dates']."</font></td></tr>";
        $page++;
    }else{
        $data .="<tr style='border: 1px solid #000000;'><td style='border: 1px solid #000000;'>".substr($k,0,3)."</td><td style='border: 1px solid #000000;'>".substr($k,3,2)."</td><td style='border: 1px solid #000000;font-size:20px;'>".$v['name']."</td><td style='border: 1px solid #000000;'>".$v['times']."</td><td>".$v['back_money']."</td><td style='border: 1px solid #000000;'>　　　　　　</td><td style='border: 1px solid #000000;'><font size=1>".$v['dates']."</font></td></tr>";
    }
    $last_class = substr($k,0,3);
}
$data .= "</table>頁：".$page."<br>承辦：";
$title= "<h1 align='center'>和東國小 ".$semester." 學期 學生午餐退費印領清冊</h1><h2>一、全校總退餐次： ".$total_times." 次</h2><h2>二、全校總退餐費： ".$total_money." 元</h2><h2>三、以下各班詳細資料共 ".$page." 頁</h2>";
$data = $title.$data;

echo "<body onload='window.print()'>";
echo $data;

?>