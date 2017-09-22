<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        return view('classrooms.index',compact('dates'));
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
