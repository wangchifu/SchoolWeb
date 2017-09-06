<?php

namespace App\Http\Controllers;
use App\Report;
use App\User;
use Illuminate\Http\Request;

class PerSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.perSetup');
    }

    public function updatePwd(Request $request)
    {
        $att = $request->all();
        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password'));
        $user = User::where('id',$att['id'])->first();
        $user->update($att);
        return redirect()->route('home');
    }
    public function updateData(Request $request,User $user)
    {
        $user->update($request->all());
        return redirect()->route('home');
    }

}
