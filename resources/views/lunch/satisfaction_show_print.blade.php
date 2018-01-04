<?php
$table = "";
$q1_1 = "";
$q1_2 = "";
$q1_3 = "";
$q1_4 = "";
$q1_5 = "";
$q2_1 = "";
$q2_2 = "";
$q3_1 = "";
$q3_2 = "";
$q3_3 = "";
$q3_4 = "";
$q3_5 = "";
$q3_6 = "";
$q3_7 = "";
$q3_8 = "";
$q3_9 = "";
$q3_10 = "";
$q4_1 = "";
$q4_2 = "";
$all_score = 0;
$all_people = 0;
foreach($class_data as $k=>$v){
    $table .="
<center><h1>".$v['semester']."學期彰化縣和美區中央廚房午餐滿意度調查表</h1></center>
<table width='800'>
    <tr><td colspan='4'>親愛的老師 您好：<br>
            　　依據合約規定，目前承包廠商是否得以下學年度續約考核分數的20％來自於『五校午餐滿意度分數』，煩請老師們撥冗調查，以班為單位，提供寶貴意見，以提昇和美區中央廚房對學校午餐供應之品質，請於指定時間前提送調查表，俾利辦理統計及後續事宜。謝謝您的配合!
        </td>
    </tr>
    <tr>
        <td>學校：和東國小</td>
        <td>班級：".$k."</td>
        <td>全班用餐人數(含導師)：".$v['class_people']." 人</td>
        <td>得分：".$v['total']."</td>
    </tr>
</table>
<table width='800' style='border: 1px solid #000000;border-collapse: collapse;'>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            內容
        </td>
        <td style='border: 1px solid #000000;' colspan= '3'>
            滿意度(%)
        </td>
        <td style='border: 1px solid #000000;'>
            備註
        </td>
    </tr>
    <tr bgcolor='#FFFF33' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            一、供應量部分(15%)
        </td>
        <td style='border: 1px solid #000000;'>
            適量(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            偶有不足(2分)
        </td>
        <td style='border: 1px solid #000000;'>
            經常不足(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.主食(飯、麵)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q1_1']."
        </td>
        <td style='border: 1px solid #000000;'>
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.主菜(肉類)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q1_2']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            3.副菜(副食品)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q1_3']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            4.青菜
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q1_4']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            5.湯品
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q1_5']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            二、口味部分(14%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(7分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(5分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.午餐之烹調口味(淡、鹹)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q2_1']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.午餐之油膩性
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q2_2']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            三、品質部分(60%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(6分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.午餐食材(青菜、肉)新鮮度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_1']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.米飯熟度及軟硬度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_2']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            3.對週五特餐日供應菜色滿意度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_3']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            4.對主食之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_4']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            5.對主菜(豬排、雞腿、魚排)之品質
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_5']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            6.對副菜之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_6']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            7.對青菜之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_7']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            8.對湯品之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_8']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            9.對水果之品質(數量、是否損傷、過熟/不熟)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_9']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            10.對乳品之品質(數量、是否破損)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q3_10']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            四、整體服務部分(11%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(5分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.服務人員態度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q4_1']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF'>
        <td style='border: 1px solid #000000;'>

        </td>
        <td style='border: 1px solid #000000;'>
            滿意(6分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0)分
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.整體而言，對學校午餐是否滿意
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$v['q4_2']."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            貴班最喜愛的菜色
        </td>
        <td style='border: 1px solid #000000;' colspan= '4'>
            ".$v['favority']."
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            其他建議
        </td>
        <td style='border: 1px solid #000000;' colspan= '4'>
            ".$v['suggest']."
        </td>
    </tr>
</table>
導師：".$v['teacher']."
<p style='page-break-after:always'></p>
";
    $q1_1 += $v['q1_1']*$v['class_people'];
    $q1_2 += $v['q1_2']*$v['class_people'];
    $q1_3 += $v['q1_3']*$v['class_people'];
    $q1_4 += $v['q1_4']*$v['class_people'];
    $q1_5 += $v['q1_5']*$v['class_people'];
    $q2_1 += $v['q2_1']*$v['class_people'];
    $q2_2 += $v['q2_2']*$v['class_people'];
    $q3_1 += $v['q3_1']*$v['class_people'];
    $q3_2 += $v['q3_2']*$v['class_people'];
    $q3_3 += $v['q3_3']*$v['class_people'];
    $q3_4 += $v['q3_4']*$v['class_people'];
    $q3_5 += $v['q3_5']*$v['class_people'];
    $q3_6 += $v['q3_6']*$v['class_people'];
    $q3_7 += $v['q3_7']*$v['class_people'];
    $q3_8 += $v['q3_8']*$v['class_people'];
    $q3_9 += $v['q3_9']*$v['class_people'];
    $q3_10 += $v['q3_10']*$v['class_people'];
    $q4_1 += $v['q4_1']*$v['class_people'];
    $q4_2 += $v['q4_2']*$v['class_people'];
    $all_score  += $v['total']*$v['class_people'];
    $all_people += $v['class_people'];
}
$average_q1_1 = round($q1_1/$all_people,2);
$average_q1_2 = round($q1_2/$all_people,2);
$average_q1_3 = round($q1_3/$all_people,2);
$average_q1_4 = round($q1_4/$all_people,2);
$average_q1_5 = round($q1_5/$all_people,2);
$average_q2_1 = round($q2_1/$all_people,2);
$average_q2_2 = round($q2_2/$all_people,2);
$average_q3_1 = round($q3_1/$all_people,2);
$average_q3_2 = round($q3_2/$all_people,2);
$average_q3_3 = round($q3_3/$all_people,2);
$average_q3_4 = round($q3_4/$all_people,2);
$average_q3_5 = round($q3_5/$all_people,2);
$average_q3_6 = round($q3_6/$all_people,2);
$average_q3_7 = round($q3_7/$all_people,2);
$average_q3_8 = round($q3_8/$all_people,2);
$average_q3_9 = round($q3_9/$all_people,2);
$average_q3_10 = round($q3_10/$all_people,2);
$average_q4_1 = round($q4_1/$all_people,2);
$average_q4_2 = round($q4_2/$all_people,2);
$average_score = round($all_score/$all_people,2);

$one = "
<center><h1>".$semester."學期彰化縣和美區中央廚房午餐滿意度調查表</h1></center>
<table width='800'>
    <tr><td colspan='4'>親愛的老師 您好：<br>
            　　依據合約規定，目前承包廠商是否得以下學年度續約考核分數的20％來自於『五校午餐滿意度分數』，煩請老師們撥冗調查，以班為單位，提供寶貴意見，以提昇和美區中央廚房對學校午餐供應之品質，請於指定時間前提送調查表，俾利辦理統計及後續事宜。謝謝您的配合!
        </td>
    </tr>
    <tr>
        <td>學校：和東國小</td>
        <td>班級：全校</td>
        <td>全班用餐人數(含導師)：".$all_people." 人</td>
        <td>平均得分：".$average_score."</td>
    </tr>
</table>
<table width='800' style='border: 1px solid #000000;border-collapse: collapse;'>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            內容
        </td>
        <td style='border: 1px solid #000000;' colspan= '3'>
            滿意度(%)
        </td>
        <td style='border: 1px solid #000000;'>
            備註
        </td>
    </tr>
    <tr bgcolor='#FFFF33' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            一、供應量部分(15%)
        </td>
        <td style='border: 1px solid #000000;'>
            適量(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            偶有不足(2分)
        </td>
        <td style='border: 1px solid #000000;'>
            經常不足(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.主食(飯、麵)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q1_1."
        </td>
        <td style='border: 1px solid #000000;'>
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.主菜(肉類)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q1_2."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            3.副菜(副食品)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q1_3."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            4.青菜
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q1_4."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            5.湯品
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q1_5."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            二、口味部分(14%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(7分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(5分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.午餐之烹調口味(淡、鹹)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q2_1."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.午餐之油膩性
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q2_2."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            三、品質部分(60%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(6分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.午餐食材(青菜、肉)新鮮度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_1."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.米飯熟度及軟硬度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_2."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            3.對週五特餐日供應菜色滿意度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_3."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            4.對主食之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_4."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            5.對主菜(豬排、雞腿、魚排)之品質
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_5."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            6.對副菜之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_6."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            7.對青菜之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_7."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            8.對湯品之品質(無異味、無異物)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_8."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            9.對水果之品質(數量、是否損傷、過熟/不熟)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_9."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            10.對乳品之品質(數量、是否破損)
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q3_10."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td style='border: 1px solid #000000;'>
            四、整體服務部分(11%)
        </td>
        <td style='border: 1px solid #000000;'>
            滿意(5分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0分)
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            1.服務人員態度
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
           ".$average_q4_1."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF'>
        <td style='border: 1px solid #000000;'>

        </td>
        <td style='border: 1px solid #000000;'>
            滿意(6分)
        </td>
        <td style='border: 1px solid #000000;'>
            尚可(3分)
        </td>
        <td style='border: 1px solid #000000;'>
            改進(0)分
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            2.整體而言，對學校午餐是否滿意
        </td>
        <td style='border: 1px solid #000000;' align='center' colspan='3'>
            ".$average_q4_2."
        </td>
        <td style='border: 1px solid #000000;'>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            貴班最喜愛的菜色
        </td>
        <td style='border: 1px solid #000000;' colspan= '4'>
            ".$favority."
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' style='border: 1px solid #000000;'>
        <td style='border: 1px solid #000000;'>
            其他建議
        </td>
        <td style='border: 1px solid #000000;' colspan= '4'>
            ".$suggest."
        </td>
    </tr>
</table>
<p style='page-break-after:always'></p>
";

$table = $one.$table;
echo "<body onload='window.print()'>";
echo $table;
?>