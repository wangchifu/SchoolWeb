<?php

namespace App\Http\Controllers;

use App\SemesterStudent;
use App\Student;
use App\User;
use App\YearClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;


class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //學年選單
        $semesters = DB::table('year_classes')
            ->select('semester')
            ->groupBy('semester')
            ->pluck('semester', 'semester')->toArray();

        $semester = $request->input('semester');


        if($semester) {
            $YearClasses = YearClass::where('semester', '=', $semester)->get();

            $year_class = [
                '一年級' => "",
                '二年級' => "",
                '三年級' => "",
                '四年級' => "",
                '五年級' => "",
                '六年級' => "",
                '特教班' => "",
                '總共' => "",
            ];

            //統計全校人數用
            $all_school = 0;

            foreach ($YearClasses as $YearClass) {
                if (substr($YearClass->year_class, 0, 1) == 1) $year_class['一年級']++;
                if (substr($YearClass->year_class, 0, 1) == 2) $year_class['二年級']++;
                if (substr($YearClass->year_class, 0, 1) == 3) $year_class['三年級']++;
                if (substr($YearClass->year_class, 0, 1) == 4) $year_class['四年級']++;
                if (substr($YearClass->year_class, 0, 1) == 5) $year_class['五年級']++;
                if (substr($YearClass->year_class, 0, 1) == 6) $year_class['六年級']++;
                if (substr($YearClass->year_class, 0, 1) == 9) $year_class['特教班']++;
                $year_class['總共']++;
                $num = 0 ;
                $boy = 0 ;
                $girl = 0 ;
                foreach($YearClass->semester_students as $semester_student){
                    if($semester_student->at_school == "1") {
                        $all_school++;
                        $num++;
                        if ($semester_student->student->sex == "1") $boy++;
                        if ($semester_student->student->sex == "2") $girl++;
                    }else{
                        $out_students[$semester_student->student->sn] = $semester_student->year_class->name." ".$semester_student->student->name;
                    }
                }
                $stud_num[$YearClass->id] = [
                    'num'=>$num,
                    'boy'=>$boy,
                    'girl'=>$girl,
                ];
            }
        }else{
            $year_class = array();
            $YearClasses = "";
            $all_school = "";
            $stud_num = "";
        }
        if(empty($out_students)) $out_students = [];

        $data = [
            'semesters'=>$semesters,
            'semester'=>$semester,
            'year_class'=>$year_class,
            'YearClasses'=>$YearClasses,
            'stud_num'=>$stud_num,
            'all_school'=>$all_school,
            'out_students'=>$out_students,
        ];


        return view('admin.studAdmin',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function storeYearClass(Request $request)
    {
        $att = array();
        $r = $request->all();
        if($r['class1'] > 0){
            for ( $i=1 ; $i<=$r['class1'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "1".sprintf("%02s",$i);
                $att['name'] = "一年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class2'] > 0){
            for ( $i=1 ; $i<=$r['class2'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "2".sprintf("%02s",$i);
                $att['name'] = "二年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class3'] > 0){
            for ( $i=1 ; $i<=$r['class3'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "3".sprintf("%02s",$i);
                $att['name'] = "三年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class4'] > 0){
            for ( $i=1 ; $i<=$r['class4'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "4".sprintf("%02s",$i);
                $att['name'] = "四年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class5'] > 0){
            for ( $i=1 ; $i<=$r['class5'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "5".sprintf("%02s",$i);
                $att['name'] = "五年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class6'] > 0){
            for ( $i=1 ; $i<=$r['class6'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "6".sprintf("%02s",$i);
                $att['name'] = "六年".$i."班";
                YearClass::create($att);

            }
        }
        if($r['class9'] > 0){
            for ( $i=1 ; $i<=$r['class9'] ; $i++ ) {
                $att['semester'] = $r['semester'];
                $att['year_class'] = "9".sprintf("%02s",$i);
                $att['name'] = "特教".$i."班";
                YearClass::create($att);

            }
        }

        return redirect()->route('admin.indexStud');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delYearClass($semester)
    {
        $YearClasses = YearClass::where('semester','=',$semester)->get();

        foreach($YearClasses as $YearClass) {
            $YearClass->delete();
            foreach ($YearClass->semester_students as $semester_student) {
                $semester_student->delete();
            }
        }

        return redirect()->route('admin.indexStud');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function importStud(Request $request)
    {
        if(Input::hasFile('csv')) {
            $filePath = $request->file('csv')->getRealPath();
            $data = Excel::load($filePath, function ($reader) {
            })->get();

            foreach ($data as $key => $value) {
                $stud_class = $value['年級'].sprintf("%02s",$value['班級']);
                $yearclass = YearClass::where('semester','=',$value['學期'])->where('year_class','=',$stud_class)->first();
                $user = User::where('name','=',$value['導師'])->first();

                //更新班級的導師
                if($user) {
                    $att1['user_id'] = $user->id;
                    $yearclass->update($att1);
                }

                //更新學生
                $att2['sn'] = $value['學號'];
                $att2['name'] = $value['姓名'];
                $att2['sex'] = $value['性別'];
                $att2['year_class_id'] = $yearclass->id;
                $att2['num'] = sprintf("%02s",$value['座號']);

                $has_student = Student::where('sn','=',$value['學號'])->first();
                if($has_student){
                    $has_student->update($att2);
                    $id = $has_student->id;
                }else{
                    $student = Student::create($att2);
                    $id = $student->id;
                }


                $att3['student_id'] = $id;
                $att3['year_class_id'] = $yearclass->id;
                $att3['num'] = sprintf("%02s",$value['座號']);
                $att3['at_school'] = 1;

                SemesterStudent::create($att3);

            }
        }
        return redirect()->route('admin.indexStud',"semester=".$value['學期']);

    }

    public function showStud(YearClass $yearClass)
    {
        $student_data = array();
        foreach ($yearClass->semester_students as $semester_student) {
            if($semester_student->at_school == "1") {
                $student_data[$semester_student->num]['id'] = $semester_student->id;
                $student_data[$semester_student->num]['stud_id'] = $semester_student->student->id;
                $student_data[$semester_student->num]['班級'] = $yearClass->year_class;
                $student_data[$semester_student->num]['姓名'] = $semester_student->student->name;
                $student_data[$semester_student->num]['學號'] = $semester_student->student->sn;
                //if ($semester_student->student->sex == "1") {
                //    $student_data[$semester_student->num]['性別'] = "男";
                //} else {
                //    $student_data[$semester_student->num]['性別'] = "女";
                //}
                $student_data[$semester_student->num]['性別'] = $semester_student->student->sex;
            }
        }
        if($student_data){
            ksort($student_data);
        }

        $users = User::all()->pluck('name', 'id')->toArray();

        $data = [
            "yearClass"=>$yearClass,
            "student_data"=>$student_data,
            "users"=>$users,
        ];

        return view('admin.studShow',$data);
    }

    public function storeClassTea(Request $request,YearClass $yearClass)
    {
        $yearClass->update($request->all());
        return redirect()->route('admin.showStud',$yearClass->id);

    }

    public function updateStud(Request $request)
    {
        $semester = $request->input('semester');
        $year_class = $request->input('year_class');
        $id = $request->input('id');

        $new_year_class = YearClass::where('semester','=',$semester)->where('year_class','=',$year_class)->first();
        $att1['year_class_id'] = $new_year_class->id;
        $att1['num'] = $request->input('num');


        $semester_student = SemesterStudent::where('id','=',$id)->first();
        $semester_student-> update($att1);

        $att2['name'] = $request->input('name');
        $att2['sex'] = $request->input('sex');
        Student::where('id','=',$semester_student->student_id)->update($att2);


        return redirect()->route('admin.showStud',$att1['year_class_id']);

    }

    public function addStud(Request $request)
    {
        $att1['sn'] = $request->input('sn');
        $att1['name'] = $request->input('name');
        $att1['sex'] = $request->input('sex');
        $student = Student::where('sn','=',$att1['sn'])->first();
        if($student){
            $student->update($att1);
        }else{
            $student =  Student::create($att1);
        }
        $att2['student_id'] = $student->id;
        $att2['year_class_id'] = $request->input('year_class_id');
        $att2['num'] = $request->input('num');
        $att2['at_school'] = "1";

        $semester_student = SemesterStudent::where('year_class_id','=',$att2['year_class_id'])->where('student_id','=',$student->id)->first();

        if($semester_student) {
            $semester_student->update($att2);
        }else{
            SemesterStudent::create($att2);
        }

        return redirect()->route('admin.showStud',$att2['year_class_id']);

    }

    public function outStud(SemesterStudent $semesterStudent)
    {

        $att['at_school'] = "0";
        $semesterStudent->update($att);
        return redirect()->route('admin.showStud',$semesterStudent->year_class_id);
    }
}