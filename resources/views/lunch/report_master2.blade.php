@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
    <style>
        .table-bordered {
            border: 1px solid #ecf0f1 !important;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 1px solid #000000 !important;
        }
    </style>
<div class="container">
    <div class="page-header">
        <h1>{{ $semester }} 學生各月請款資料</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_master1', 'method' => 'POST']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                請先選擇餐期：{{ Form::select('order_id', $orders, $this_order_id, ['id' => 'order_id', 'class' => 'form-control', 'placeholder' => '請先選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $mon }} 月學生請款資料</h4>
                </div>
                <div class="panel-content">
                    <h2>支出分攤</h2>
                    <div class="well">
                        <table class="table table-bordered">
                            <tr>
                                <th class="col-md-6">
                                    項目
                                </th>
                                <th>
                                    金額
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    補助款計 (一般生餐數 x 部分補助金額 + 弱勢生餐數 x 全額補助金額)
                                </td>
                                <td>
                                    {{ $total_g }} x {{ $support_part_money }} + {{ $total_w }} x {{ $support_all_money }} = {{ $total_g * $support_part_money + $total_w * $support_all_money }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    自付款計 (一般生餐數 x 自付金額)
                                </td>
                                <td>
                                    {{ $total_g }} x {{ $stud_money }} = {{ $total_g * $stud_money }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    合計
                                </td>
                                <td>
                                    供餐數量：{{ $total_g+$total_w }} 餐次 ； 合計： {{ $total_g * $support_part_money + $total_w * $support_all_money + $total_g * $stud_money }} 元 ({{ $total_g+$total_w }} x {{ $support_all_money }})
                                </td>
                            </tr>
                        </table>
                    </div>
                    <h2>結算統計表</h2>
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th rowspan="2">
                                月份
                            </th>
                            <th rowspan="2">
                                項目名稱
                            </th>
                            <th rowspan="2">
                                單價
                            </th>
                            <th >
                                部分補助餐數
                            </th>
                            <th>
                                全額補助餐數
                            </th>
                            <th colspan="2">
                                部分補助金額
                            </th>
                            <th colspan="2">
                                自付金額
                            </th>
                            <th rowspan="2">
                                全額補助金額
                            </th>
                            <th rowspan="2">
                                合計
                            </th>
                            <th rowspan="2">
                                備註
                            </th>
                        </tr>
                        <tr class="bg-info">
                            <th colspan="2">
                                本月份用餐數
                            </th>
                            <th>
                                金額
                            </th>
                            <th>
                                小計
                            </th>
                            <th>
                                金額
                            </th>
                            <th>
                                小計
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_part = 0;
                        $total_self =0;
                        $total_all=0;
                        $total_class = 0;
                        $last_year = 0;
                        ?>
                        @foreach($class_data as $k => $v)
                            <?php
                            if($last_year != substr($k,0,1)){
                                $style = "style='border-top-style: outset;border-color:#000000;'";
                            }else{
                                $style = "";
                            }
                            ?>
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';" {!! $style !!}>
                            <td>
                                {{ substr($mon,5,2) }}
                            </td>
                            <td>
                                {{ $k }}
                            </td>
                            <td>
                                {{ $support_all_money }}
                            </td>
                            <td>
                                {{ $v['g'] }}
                            </td>
                            <td>
                                {{ $v['w'] }}
                            </td>
                            <td>
                                {{ $support_part_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $support_part_money }}
                            </td>
                            <td>
                                {{ $stud_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $stud_money }}
                            </td>
                            <td>
                                {{ $v['w'] * $support_all_money }}
                            </td>
                            <td>
                                {{ $v['g'] * $support_part_money + $v['g'] * $stud_money + $v['w'] * $support_all_money }}
                            </td>
                            <?php
                                $total_part += $v['g'] * $support_part_money;
                                $total_self += $v['g'] * $stud_money;
                                $total_all += $v['w'] * $support_all_money;
                                $total_class += $v['g'] * $support_part_money + $v['g'] * $stud_money + $v['w'] * $support_all_money;
                                $last_year = substr($k,0,1);
                            ?>
                            <td>

                            </td>
                        </tr>
                        @endforeach
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';" style="border-top-style: outset;border-color:#000000;">
                            <td>
                                合計
                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $total_part }}
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $total_self }}
                            </td>
                            <td>
                                {{ $total_all }}
                            </td>
                            <td>
                                {{ $total_class }}
                            </td>
                            <td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h2>供餐計價明細</h2>
                    <div class="well">
                        <table class="table table-bordered">
                            <tr>
                                <th rowspan="2">
                                    年度
                                </th>
                                <th rowspan="2">
                                    月份
                                </th>
                                <th rowspan="2">
                                    名稱
                                </th>
                                <th rowspan="2">
                                    單位
                                </th>
                                <th rowspan="2">
                                    單價
                                </th>
                                <th colspan="2">
                                    收入金額
                                </th>
                                <th colspan="2">
                                    本期支出
                                </th>
                                <th colspan="2">
                                    差異金額
                                </th>
                                <th rowspan="2">
                                    備註
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    數量
                                </th>
                                <th>
                                    金額
                                </th>
                                <th>
                                    數量
                                </th>
                                <th>
                                    金額
                                </th>
                                <th>
                                    增加
                                </th>
                                <th>
                                    減少
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    {{ $semester }}
                                </td>
                                <td>
                                    {{ $mon }}
                                </td>
                                <td>
                                    學生午餐(補助款-全額)
                                </td>
                                <td>
                                    份
                                </td>
                                <td>
                                    {{ $support_all_money }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    {{ $total_w }}
                                </td>
                                <td>
                                    {{ $support_all_money * $total_w }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    學生午餐(補助款-部份)
                                </td>
                                <td>
                                    份
                                </td>
                                <td>
                                    {{ $support_part_money }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    {{ $total_g }}
                                </td>
                                <td>
                                    {{ $support_part_money * $total_g }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr bgcolor="gray">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    小計
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                                <td>
                                    {{ $support_part_money * $total_g + $support_all_money * $total_w }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    學生午餐(自付款)
                                </td>
                                <td>
                                    份
                                </td>
                                <td>
                                    {{ $stud_money }}
                                </td>
                                <td>
                                    {{ $mon_eat_days[$mon] * $total_stu_order_num }}<br><button class="btn btn-danger btn-xs">A1</button>
                                </td>
                                <td>
                                    {{ $mon_eat_days[$mon] * $total_stu_order_num * $stud_money}}<br><button class="btn btn-warning btn-xs">B1</button>
                                </td>
                                <td>
                                    {{ $total_g }}<br><button class="btn btn-danger btn-xs">A2</button>
                                </td>
                                <td>
                                    {{ $stud_money * $total_g }}<br><button class="btn btn-warning btn-xs">B2</button>
                                </td>
                                <td>
                                    <br><button class="btn btn-success btn-xs">C1</button>
                                </td>
                                <td>
                                     <br><button class="btn btn-success btn-xs">C2</button>
                                </td>
                                <td>
                                     <br><button class="btn btn-primary btn-xs">D</button>
                                </td>
                            </tr>
                            <tr bgcolor="gray">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    合計
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                                <td>
                                    {{ $support_part_money * $total_g + $support_all_money * $total_w + $stud_money * $total_g }}
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>

                                </td>
                            </tr>
                        </table>
                        <span class="text-primary">特別注意 A1 和 A2 ； B1 和 B2 是否相同，將差異寫在 C1 或是 C2 ，原因寫於 D。</span><br>
                        <span class="text-danger">本月退餐退費為： 元</span><br>
                        <span class="text-danger">本月轉出或臨時不訂學生退費為： -  元</span><br>
                        <span class="text-success">本月轉入或臨時補訂學生收費為： +  元</span><br>
                    </div>
                    註1：本學期初 學生自付款 各月收費<br>
                    <table class="table table-bordered">
                        <tr>
                            <th>
                                月份
                            </th>
                            <th>
                                供餐日數
                            </th>
                            <th>
                                期初訂餐人數
                            </th>
                            <th>
                                自費
                            </th>
                            <th>
                                收費小計(供餐日數 x 期初訂餐人數 x 自費)
                            </th>
                        </tr>
                        <?php $tt=0; ?>
                        @foreach($mon_eat_days as $k=>$v)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <th>
                                    {{ $k }}
                                </th>
                                <th>
                                    {{ $v }}
                                </th>
                                <th>
                                    {{ $total_stu_order_num }}
                                </th>
                                <th>
                                    {{ $stud_money }}
                                </th>
                                <th>
                                    {{ $v * $total_stu_order_num * $stud_money}}
                                    <?php $tt += $v * $total_stu_order_num * $stud_money; ?>
                                </th>
                            </tr>
                        @endforeach
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                            <th>
                                合計
                            </th>
                            <th>

                            </th>
                            <th>

                            </th>
                            <th>

                            </th>
                            <th>
                                {{ $tt }}
                            </th>
                        </tr>
                    </table>
                    <br>
                    註2：學生各月退餐退費表<br>
                    <table>

                    </table>
                    <br>
                    註3：轉出或臨時不訂學生各月退費表<br>
                    <table>

                    </table>
                    <br>
                    註4：轉入或臨時補訂學生各月收費表<br>
                    <table>

                    </table>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection