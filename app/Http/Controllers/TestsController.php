<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;

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
    public function destroy($id)
    {
        //
    }
}
