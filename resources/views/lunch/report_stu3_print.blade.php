<?php
$num = 1;
$last_class = "";
$all = "<table cellspacing='10' cellpadding='10'><tr>";
foreach($abs_data as $k=>$v){
    if($last_class != substr($k,0,3) and !empty($last_class) and $num%8 !=1){
        if($num%2==0) $all .="<td></td>";
        $all .= "</tr></table><p style='page-break-after:always'></p><table cellspacing='10' cellpadding='10'><tr>";
        $num=1;
    }
    $one="
			<td width=50%>和東國小".$semester."學期學生午餐請假退費通知：
			<table style='border: 1px solid #000000;border-collapse: collapse;'><tr style='border: 1px solid #000000;'>
			<tr style='border: 1px solid #000000;'><td>班級座號".$k.$v['name']."</td><td></td></tr>
			<tr><td>退餐次數：".$v['times']." 天</td><td>退費：".$v['back_money']." 元</td></tr>
			<tr><td colspan=2>說明：退費將退至學費扣款帳戶內；現金繳學費者，退現金予學生帶回。</td></tr>
			<tr><td colspan=2>退餐日期：".$v['dates']."</td></tr>
			</table></td>";


    if($num%2==0 and $num%8 <>0){
        $all .=$one."</tr><tr>";
    }elseif($num%8 == 0){
        $all .=$one."</tr></table><p style='page-break-after:always'></p><table cellspacing='10' cellpadding='10'><tr>";
    }else{
        $all .= $one;
    }
    $last_class = substr($k,0,3);
    $one = "";
    $num++;
}
$all .= "</tr></table>";
echo "<body onload='window.print()'>";
echo $all;
?>