<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\Test;
use Illuminate\Http\Request;
use Excel;

class TestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group_id2 = (auth()->user()->group_id2)?auth()->user()->group_id2:"0";
        $tests = Test::orderBy('id')->where('active','=','1')->where('do','like','%'. auth()->user()->group_id .'%')->orwhere('do','like','%'. $group_id2 .'%')->get();
        return view('tests.index',compact('tests'));
    }
    public function admin()
    {
        $user = auth()->user();
        if ($user->can('create', Test::class)) {
            $tests = Test::orderBy('id','DESC')->get();
            return view('tests.admin',compact('tests'));
        }else{
            return redirect()->route('tests.index');
        }
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
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        Test::create($att);
        return redirect()->route('tests.admin');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, Test $test)
    {
        $att= $request->all();
        if($request->input('active')==null) $att['active']=null;
        $test->update($att);
        return redirect()->route('tests.admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        $test->delete();
        Question::where('test_id','=',$test->id)->delete();
        Answer::where('test_id','=',$test->id)->delete();
        return redirect()->route('tests.admin');
    }
    public function download(Request $request,Test $test)
    {

        if($test->user_id == auth()->user()->id){

            $type = $request->input('type');

            $questions = Question::where('test_id','=',$test->id)->orderBy('order')->get();

            $row1 = array('作答者');
            foreach($questions as $question){

                array_push($row1,$question->title);

                foreach($question->answers as $answer){
                    if(!isset($user_data[$answer->user->order_by])){
                        $user_data[$answer->user->order_by][0] = $answer->user->name;
                        array_push($user_data[$answer->user->order_by],$answer->answer);
                    }else {
                        array_push($user_data[$answer->user->order_by],$answer->answer);
                    }
                }
            }

            ksort($user_data);

            $cellData = array($row1);
            foreach($user_data as $k=>$v){
                array_push($cellData,$v);
            }

            Excel::create($test->name,function($excel) use ($cellData){
                $excel->sheet('score', function($sheet) use ($cellData){
                    $sheet->rows($cellData);
                });
            })->export($type);
        }else{
            return redirect()->route('tests.index');
        }
    }
}
