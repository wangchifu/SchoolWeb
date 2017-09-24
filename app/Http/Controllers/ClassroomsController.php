<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\OrderClassroom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassroomsController extends Controller
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
    public function index(Request $request)
    {
        $classroom_id = ($request->input('classroom_id'))?$request->input('classroom_id'):"";
        if($classroom_id !="") {
            $classroom = Classroom::where('id', '=', $classroom_id)->first();
        }else{
            $classroom = "";
        }
        $classrooms_menu = Classroom::where('active','=','1')->get()->pluck('name','id')->toArray();

        $this_date = ($request->input('this_date'))?$request->input('this_date'):date('Y-m-d');
        $d = explode("-",$this_date);
        $dt = Carbon::create($d[0],$d[1],$d[2],0);

        $dates=[
            "d0"=>$dt->format('Y-m-d-w'),
            "d1"=>$dt->addDay()->format('Y-m-d-w'),
            "d2"=>$dt->addDay()->format('Y-m-d-w'),
            "d3"=>$dt->addDay()->format('Y-m-d-w'),
            "d4"=>$dt->addDay()->format('Y-m-d-w'),
            "d5"=>$dt->addDay()->format('Y-m-d-w'),
            "d6"=>$dt->addDay()->format('Y-m-d-w'),
            ];

        return view('classrooms.index',compact('dates','classrooms_menu','classroom'));
    }
    public function admin()
    {
        $classrooms = Classroom::orderBy('id')->get();
        return view('classrooms.admin',compact('classrooms'));
    }
    public function addClassroom(Request $request)
    {
        Classroom::create($request->all());
        return redirect()->route('classrooms.admin');
    }
    public function updateClassroom(Request $request)
    {

    }
    public function delClassrom(Classroom $classroom)
    {
        //
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
    public function storeOrder(Request $request)
    {
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        OrderClassroom::create($att);
        $data = [
            'classroom_id'=>$request->input('classroom_id'),
            'this_date'=>$request->input('orderDate'),
        ];
        return redirect()->route('classrooms.index',$data);
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
    public function destroy($id)
    {
        //
    }
}
