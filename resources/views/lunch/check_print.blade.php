<?php
$data ="";
    foreach($class_tea as $k2 => $v2){
        $data .="
        <center><h1>和東國小 {$semester} 學期 學生午餐 供餐檢核表</h1></center>
        班級：{$v2['name']} 月份：{$mon}
        <center>
        <table style='border: 1px solid #000000;border-collapse: collapse;'>
            <tr style='border: 1px solid #000000;font-size: 20px;'>
                <td style='border: 1px solid #000000;width:150px'>
                    日期
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    主食
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    主菜
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    副菜
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    蔬菜
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    湯品</td>
                <td style='border: 1px solid #000000;width:180px'>
                    不合格原因
                </td>
                <td style='border: 1px solid #000000;width:50px'>
                    處置
                </td>
            </tr>";
        foreach($dates as $k3=>$v3){
            if($v3 == "1" and substr($k3,0,7) == $mon){
                if(!isset($check_data[$k2][$k3]['main_eat'])) $check_data[$k2][$k3]['main_eat'] = null;
                if(!isset($check_data[$k2][$k3]['main_vag'])) $check_data[$k2][$k3]['main_vag'] = null;
                if(!isset($check_data[$k2][$k3]['co_vag'])) $check_data[$k2][$k3]['co_vag'] = null;
                if(!isset($check_data[$k2][$k3]['vag'])) $check_data[$k2][$k3]['vag'] = null;
                if(!isset($check_data[$k2][$k3]['soup'])) $check_data[$k2][$k3]['soup'] = null;
                if(!isset($check_data[$k2][$k3]['reason'])) $check_data[$k2][$k3]['reason'] = null;
                if(!isset($check_data[$k2][$k3]['action'])) $check_data[$k2][$k3]['action'] = null;
                if($check_data[$k2][$k3]['main_eat'] == "1"){
                    $img1 = "<img src='". asset('img/no_check.png') ."'>";
                }else{
                    $img1 = "<img src='". asset('img/check.png') ."'>";
                }
                if($check_data[$k2][$k3]['main_vag'] == "1"){
                    $img2 = "<img src='". asset('img/no_check.png') ."'>";
                }else{
                    $img2 = "<img src='". asset('img/check.png') ."'>";
                }
                if($check_data[$k2][$k3]['co_vag'] == "1"){
                    $img3 = "<img src='". asset('img/no_check.png') ."'>";
                }else{
                    $img3 = "<img src='". asset('img/check.png') ."'>";
                }
                if($check_data[$k2][$k3]['vag'] == "1"){
                    $img4 = "<img src='". asset('img/no_check.png') ."'>";
                }else{
                    $img4 = "<img src='". asset('img/check.png') ."'>";
                }
                if($check_data[$k2][$k3]['soup'] == "1"){
                    $img5 = "<img src='". asset('img/no_check.png') ."'>";
                }else{
                    $img5 = "<img src='". asset('img/check.png') ."'>";
                }
                $data .= "
                <tr style='border: 1px solid #000000;'>
                    <td align='center' style='border: 1px solid #000000;font-size: 30px;'>
                        {$k3}
                    </td>
                    <td align='center' style='border: 1px solid #000000;'>
                        {$img1}
                    </td>
                    <td align='center' style='border: 1px solid #000000;'>
                        {$img2}
                    </td>
                    <td align='center' style='border: 1px solid #000000;'>
                        {$img3}
                    </td>
                    <td align='center' style='border: 1px solid #000000;'>
                        {$img4}
                    </td>
                    <td align='center' style='border: 1px solid #000000;'>
                        {$img5}
                    </td>
                    <td style='border: 1px solid #000000;'>
                        {$check_data[$k2][$k3]['reason']}
                    </td>
                    <td style='border: 1px solid #000000;'>
                        {$check_data[$k2][$k3]['action']}
                    </td>
                </tr>";
            }
        }
        $data .= "</table></center>級任老師：{$v2['tea']}<p style='page-break-after:always'></p>";
    }

echo "<body onload='window.print()'>";
echo $data;

?>