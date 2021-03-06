@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li class="active"><a href="{{ url('admin/studAdmin') }}">學生管理</a></li>
        <li><a href="{{ url('admin/funAdmin') }}">指定管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-8">
            <div class="well">
                <h4>查詢學期</h4>
                {{ Form::open(['route' => 'admin.indexStud', 'method' => 'POST']) }}
                {{ Form::select('semester', $semesters, $semester, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '請選擇學期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            @if($semester)
                @if($all_school == "0")
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>學生管理</h4>
                        </div>
                        <div class="panel-body forum-content">
                            <div class="text-danger">1.請先確認新學期的班級資料已建立後，再行上傳！</div>
                            <div class="text-danger">3.同學期已經上傳過的，請勿再上傳！</div><br>
                            {{ Form::open(['route' => 'admin.importStud', 'method' => 'POST','files'=>true]) }}
                            <table>
                            <tr>
                                <td><input name="csv" type="file" required="required" multiple></td><td><button type="submit" class="btn btn-info">上傳CSV檔</button></td>
                            </tr>
                            </table>
                            {{ Form::close() }}
                            <a href="../demo.csv" class="btn btn-primary"><span class="glyphicon glyphicon-download-alt"></span> CSV範例檔</a>
                        </div>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>{{ $semester }} 學期 班級學生資料</h4>
                            <div class="text-right"><a href="{{ route('admin.delYearClass',$semester) }}" id="DelLink" class="btn btn-danger" onclick="bbconfirm2('DelLink','你確定要刪除全學期的班級設定嗎？')">刪除重置</a></div>
                    </div>
                    <div class="panel-body forum-content">
                        <h4>班級數</h4>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    年級
                                </th>
                                <th>
                                    班級數
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                        @if($year_class)
                            @foreach($year_class as $k=>$v)
                                <tr>
                                    <td>
                                        {{ $k }}
                                    </td>
                                    <td>
                                        {{ $v }} 班
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                            <tr>
                                <td>
                                    全校人數
                                </td>
                                <td>
                                    {{ $all_school }} 人
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md-4">
            <div class="well">
                <h4>新增學年班級</h4>
                {{ Form::open(['route' => 'admin.storeYearClass', 'method' => 'POST']) }}
                <table class="table table-striped">
                    <tr>
                        <td>
                            學年學期
                        </td>
                        <td>
                            {{ Form::text('semester', null, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '學年學期：1061','maxlength'=>'4','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            一年級
                        </td>
                        <td>
                            {{ Form::text('class1', null, ['id' => 'class1', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            二年級
                        </td>
                        <td>
                            {{ Form::text('class2', null, ['id' => 'class2', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            三年級
                        </td>
                        <td>
                            {{ Form::text('class3', null, ['id' => 'class3', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            四年級
                        </td>
                        <td>
                            {{ Form::text('class4', null, ['id' => 'class4', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            五年級
                        </td>
                        <td>
                            {{ Form::text('class5', null, ['id' => 'class5', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            六年級
                        </td>
                        <td>
                            {{ Form::text('class6', null, ['id' => 'class6', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            特教班
                        </td>
                        <td>
                            {{ Form::text('class9', null, ['id' => 'class9', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                </table>
                <button class="btn btn-success">新增</button>


                {{ Form::close() }}
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $semester }} 學期 各班詳細資料</h4>
                </div>
                <div class="panel-body forum-content">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                班級代號
                            </th>
                            <th>
                                班級名稱
                            </th>
                            <th>
                                班級人數 ( 男；女 )
                            </th>
                            <th>
                                級任老師
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($YearClasses)
                            {{ Form::open(['route'=>'admin.store_class_tea','method'=>'post','id'=>'store_class_tea','onsubmit'=>'return false']) }}
                            <tr>
                                <td>
                                    <a href="#" class="btn btn-primary" onclick="bbconfirm('store_class_tea','確定嗎？')">確定級任老師</a>
                                </td>
                            </tr>
                            @foreach($YearClasses as $YearClass)
                                <tr>
                                    <td>
                                        {{ $YearClass->year_class }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.showStud',$YearClass->id) }}" class="btn btn-success">{{ $YearClass->name }}</a>
                                    </td>
                                    <td>
                                        {{ $stud_num[$YearClass->id]['num'] }} 人 ( 男： {{ $stud_num[$YearClass->id]['boy'] }} 人 ； 女： {{ $stud_num[$YearClass->id]['girl'] }} 人)
                                    </td>
                                    <td>
                                        @if($YearClass->user_id)
                                            {{ $YearClass->user->name }}
                                        @else
                                            {{ Form::select('class_tea['.$YearClass->id.']', $tea_menu, null, ['class' => 'form-control', 'placeholder' => '請選擇級任']) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <a href="#" class="btn btn-primary" onclick="bbconfirm('store_class_tea','確定嗎？')">確定級任老師</a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="well">
                <h4>本學期轉出名單</h4>
                <table class="table table-striped">
                    <thead>
                    <th>學號</th>
                    <th>班級 姓名</th>
                    </thead>
                    <tbody>
                @foreach($out_students as $k=>$v)
                    <tr>
                        <td>
                            {{$k}}
                        </td>
                        <td>
                            {{$v}}
                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
@include('layouts.partials.bootbox')