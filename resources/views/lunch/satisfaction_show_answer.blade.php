<h1>{{ $satisfaction_class->lunch_satisfaction->semester }}學期彰化縣和美區中央廚房午餐滿意度調查表</h1>
<table width='800'>
    <tr><td colspan= '4'>親愛的老師 您好：<br>
            　　依據合約規定，目前承包廠商是否得以下學年度續約考核分數的20％來自於『五校午餐滿意度分數』，煩請老師們撥冗調查，以班為單位，提供寶貴意見，以提昇和美區中央廚房對學校午餐供應之品質，請於指定時間前提送調查表，俾利辦理統計及後續事宜。謝謝您的配合!
        </td>
    </tr>
    <tr>
        <td>學校：和東國小</td>
        <td>班級：{{ $satisfaction_class->class_id }}</td>
        <td>全班用餐人數(含導師)：{{ $satisfaction_class->class_people }} 人</td>
        <td>得分：{{ $total }}</td>
    </tr>
</table>
<table width='800' cellspacing='1' cellpadding='3' bgcolor='#000000'>
    <tr bgcolor='#FFFFFF'>
        <td>
            內容
        </td>
        <td colspan= '3'>
            滿意度(%)
        </td>
        <td>
            備註
        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td>
            一、供應量部分(15%)
        </td>
        <td>
            適量(3分)
        </td>
        <td>
            偶有不足(2分)
        </td>
        <td>
            經常不足(0分)
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            1.主食(飯、麵)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q1_1 }}
        </td>
        <td>
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            2.主菜(肉類)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q1_2 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            3.副菜(副食品)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q1_3 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            4.青菜
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q1_4 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            5.湯品
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q1_5 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td>
            二、口味部分(14%)
        </td>
        <td>
            滿意(7分)
        </td>
        <td>
            尚可(5分)
        </td>
        <td>
            改進(0分)
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            1.午餐之烹調口味(淡、鹹)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q2_1 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            2.午餐之油膩性
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q2_2 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td>
            三、品質部分(60%)
        </td>
        <td>
            滿意(6分)
        </td>
        <td>
            尚可(3分)
        </td>
        <td>
            改進(0分)
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            1.午餐食材(青菜、肉)新鮮度
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_1 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            2.米飯熟度及軟硬度
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_2 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            3.對週五特餐日供應菜色滿意度
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_3 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            4.對主食之品質(無異味、無異物)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_4 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            5.對主菜(豬排、雞腿、魚排)之品質
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_5 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            6.對副菜之品質(無異味、無異物)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_6 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            7.對青菜之品質(無異味、無異物)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_7 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            8.對湯品之品質(無異味、無異物)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_8 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            9.對水果之品質(數量、是否損傷、過熟/不熟)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_9 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            10.對乳品之品質(數量、是否破損)
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q3_10 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFF33'>
        <td>
            四、整體服務部分(11%)
        </td>
        <td>
            滿意(5分)
        </td>
        <td>
            尚可(3分)
        </td>
        <td>
            改進(0分)
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            1.服務人員態度
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q4_1 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF'>
        <td>

        </td>
        <td>
            滿意(6分)
        </td>
        <td>
            尚可(3分)
        </td>
        <td>
            改進(0)分
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            2.整體而言，對學校午餐是否滿意
        </td>
        <td align="center" colspan="3">
            {{ $satisfaction_class->q4_2 }}
        </td>
        <td>

        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            貴班最喜愛的菜色
        </td>
        <td colspan= '4'>
            {{ $satisfaction_class->favority }}
        </td>
    </tr>
    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
        <td>
            其他建議
        </td>
        <td colspan= '4'>
            {{ $satisfaction_class->suggest }}
        </td>
    </tr>
</table>