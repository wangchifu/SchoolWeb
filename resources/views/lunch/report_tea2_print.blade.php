<?php
$num = 1;
$table = "<table>";
foreach($user_datas as $k1 => $v1){
    $count = 0;
    $one = "
        <span style='font-size:20px;'>{$num}-{$k1}</span>
        <table cellPadding='0' border=1 cellSpacing='0' style='border-bottom-style:none;border-top-style:none;border-left-style:none;border-right-style:none;border-collapse:collapse;font-size:10pt'>
        <tr bgcolor='888888' style='font-size:11pt;font-weight:bold;'><td style='width:80px;'>餐期</td><td style='width:60px;'>日數</td><td style='width:50px;'>費用</td></tr>";
    foreach($v1 as $k2 => $v2){
        $money = $tea_money*$v2;
        $one .= "<tr bgcolor='#FFFFFF'><td>{$k2}</td><td>{$v2}</td><td>{$money}</td></tr>";
        $count += $v2;
    }
    $total_money = $tea_money*$count;
    $one .="<tr bgcolor='#FFFFFF'><td>合計</td><td>{$count}</td><td style='font-size:20px;'>{$total_money}</td></tr><tr bgcolor='#FFFFFF'><td>承辦簽收</td><td colspan='2'>　<br>　</td></tr></table>";
    $num++;
    $table .= "<tr><td style='width:210px;border-right-style:dotted;border-right-color:#C0C0C0;padding-right:8px;' align='center'>和東教職午餐-存根<br>{$one}</td><td style='width:210px;border-right-style:dotted;border-right-color:#C0C0C0;padding-right:8px;' align='center'>和東教職午餐-收據<br>{$one}</td><td style='width:210px;border-right-style:dotted;border-right-color:#C0C0C0;padding-right:8px;' align='center'>和東教職午餐-通知<br>{$one}</td></tr></table><div style='margin: 10px 8px 10px 6px;border-top:3px dotted #C0C0C0;'></div>";
    if($num%5==0) $table .= "<p style='page-break-after:always'></p>";
    $table .= "<table>";
}
echo "<body onload='window.print()'>";
echo $table;
?>