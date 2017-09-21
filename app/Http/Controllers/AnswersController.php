<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\Test;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Test $test)
    {
        $questions = Question::where('test_id','=',$test->id)->orderBy('order')->get();
        return view('answers.create',compact('test','questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach($request->input('Q') as $k=>$v){
            $question = Question::where('id','=',$k)->first();
            if($question->type =="checkbox" or $question->type =="radio"){
                foreach($v as $k1=>$v1){
                    $att['answer'] .= $v1." ; ";
                }
                $att['answer'] = substr($att['answer'],0,-3);
            }else{
                $att['answer'] = $v;
            }

            $att['question_id'] = $k;
            $att['user_id'] = auth()->user()->id;
            $att['test_id'] = $request->input('test_id');
            Answer::create($att);
            $att['answer'] = "";
        }
        return redirect()->route('tests.index');
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
    public function destroy($test_id)
    {
        Answer::where('test_id','=',$test_id)->where('user_id','=',auth()->user()->id)->delete();
        return redirect()->route('tests.index');
    }
}
