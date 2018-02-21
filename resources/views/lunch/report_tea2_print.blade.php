<?php
/**
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
 * */

 $date = explode('-',date('Y-m-d'));
 $chy = $date[0]-1911;
 $num = $semester."001";
echo "<body onload='window.print()'>";
$i=1;
foreach($user_datas as $k1 => $v1){
    $table = "
              中華民國{$chy}年{$date[1]}月{$date[2]}日　　　　　　　　　　　　　　　　　　　　　　　　　　　彰和東午字第{$num}號
              <table cellPadding='0' width='800' border=1 cellSpacing='0' style='border-bottom-style:none;border-top-style:none;border-left-style:none;border-right-style:none;border-collapse:collapse;font-size:18pt'>";
    $count = 0;
    $table .= "
               <tr>
                <td align='center' width='250'>繳　款　人</td>
                <td colspan='2'>{$i}-{$k1}</td>
               </tr>
               <tr>
                <td align='center'>明細</td>
                <td colspan='2'>
                <table width='100%' cellSpacing='0' cellPadding='0'>";
                foreach($v1 as $k2 => $v2){
                $money = $tea_money*$v2;
                $count += $v2;
                $table .= "
                <tr><td>餐期：{$k2}</td><td>訂餐數：{$v2}</td><td>小計：{$money}</td></tr>
                ";
                }
                $total_money = $tea_money * $count;
                $cht_monty = num2str($total_money);
                $total_money2 = number_format($total_money);
      $table .="
                </table>
                </td>
               </tr>
               <tr>
               <td align='center'>新　臺　幣</td><td>{$cht_monty}</td><td>$ {$total_money2}</td>
               </tr>
               <tr>
               <td align='center'>事　　由</td>
               <td colspan='2'>教職員午餐繳費</td>
               </tr>
               </table>
               <p stype='font-size:20px'>承辦人　　　　　　　　　主辨出納　　　　　　　　　主辨會計　　　　　　　　　機關長官</p>
               ";

    $total_table = "<h2 align='center'>彰化縣和美鎮和東國民小學 收款收據 (收執聯)</h2>".$table."<hr>";
    $total_table .= "<h2 align='center'>彰化縣和美鎮和東國民小學 收款收據 (報核聯)</h2>".$table."<hr>";
    $total_table .= "<h2 align='center'>彰化縣和美鎮和東國民小學 收款收據 (存根聯)</h2>".$table."<p style='page-break-after:always'></p>";

    echo $total_table;

      $num++;
      $i++;

}



function num2str($num){
    $string = "";
    $numc ="零,壹,貳,參,肆,伍,陸,柒,捌,玖";
    $unic = ",拾,佰,仟";
    $unic1 = "元整,萬,億,兆,京";
    $numc_arr = explode(",", $numc);
    $unic_arr = explode(",", $unic);
    $unic1_arr = explode(",", $unic1);
    $i = str_replace(",", "", $num);
    $c0 = 0;
    $str = array();
    do{
        $aa = 0;
        $c1 = 0;
        $s = "";
        $lan = (strlen($i) >= 4) ? 4 : strlen($i);
        $j = substr($i, -$lan);
        while($j > 0){
            $k = $j % 10;
            if($k > 0) {
                $aa = 1;
                $s = $numc_arr[$k].$unic_arr[$c1].$s;
            }elseif($k == 0) {
                if($aa == 1) $s = "0".$s;
            }
            $j = intval($j / 10);
            $c1 += 1;
        }
        $str[$c0] = ($s == '') ? '' : $s.$unic1_arr[$c0];
        $count_len = strlen($i) - 4;
        $i = ($count_len > 0) ? substr($i, 0, $count_len) : '';
        $c0 += 1;
    }while($i != '');
    foreach($str as $v) $string .= array_pop($str);
    $string = preg_replace('/0+/', '零', $string);
    return $string;
}
?>