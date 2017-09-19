<?php

namespace App\Http\Controllers;

use App\Fix;
use App\Fun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FixesController extends Controller
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
        $funs = Fun::where('type','1')->get();
        return view('fixes.index',compact('funs'));
    }
    public function select($id)
    {
        $fun = Fun::where('id',$id)->first();

        $undone = Input::get('undone');
        if($undone){
            $fixes = Fix::where('fun_id',$id)->where('done','=',null)->orderBy('id','DESC')->paginate(50);
        }else{
            $fixes = Fix::where('fun_id',$id)->orderBy('id','DESC')->paginate(10);
        }


        $data = compact('fun','fixes');
        return view('fixes.select',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Fun $fun)
    {
        return view('fixes.create',compact('fun'));
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
        Fix::create($att);

        return redirect()->route('fixes.select',$request->input('fun_id'));
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
    public function update(Request $request, Fix $fix)
    {
        $att= $request->all();
        if($request->input('done')==null) $att['done']=null;
        $fix->update($att);
        return redirect()->route('fixes.select',$fix->fun->id);
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
