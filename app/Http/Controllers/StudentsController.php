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

            foreach ($YearClasses as $YearClass) {
                if (substr($YearClass->year_class, 0, 1) == 1) $year_class['一年級']++;
                if (substr($YearClass->year_class, 0, 1) == 2) $year_class['二年級']++;
                if (substr($YearClass->year_class, 0, 1) == 3) $year_class['三年級']++;
                if (substr($YearClass->year_class, 0, 1) == 4) $year_class['四年級']++;
                if (substr($YearClass->year_class, 0, 1) == 5) $year_class['五年級']++;
                if (substr($YearClass->year_class, 0, 1) == 6) $year_class['六年級']++;
                if (substr($YearClass->year_class, 0, 1) == 9) $year_class['特教班']++;
                $year_class['總共']++;
            }
        }else{
            $year_class = array();
            $YearClasses = "";

        }

        $data = [
            'semesters'=>$semesters,
            'semester'=>$semester,

            'year_class'=>$year_class,
            'YearClasses'=>$YearClasses,
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
        YearClass::where('semester','=',$semester)->delete();

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

                if($user) {
                    $att1['user_id'] = $user->id;
                    $yearclass->update($att1);
                }



                $att2['sn'] = $value['學號'];
                $att2['name'] = $value['姓名'];
                $att2['sex'] = $value['性別'];
                $att2['YearClass_id'] = $yearclass->id;
                $att2['num'] = sprintf("%02s",$value['座號']);
                $att2['at_school'] = 1;
                $student = Student::create($att2);

                $att3['student_id'] = $student->id;
                $att3['YearClass_id'] = $yearclass->id;
                $att3['num'] = sprintf("%02s",$value['座號']);

                SemesterStudent::create($att3);

            }
        }
        return redirect()->route('admin.indexStud',"semester=".$value['學期']);

    }
}