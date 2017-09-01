<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.reset-password');
    }

    public function update(Request $request)
    {
        $att = $request->all();
        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password'));
        $user = User::where('id',$att['id'])->first();
        $user->update($att);
        return redirect()->route('home');
    }

}
