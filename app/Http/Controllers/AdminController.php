<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Report;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users1 = User::orderBy('order_by', 'ASC')->where('unactive',null)->get();
        $users2 = User::orderBy('order_by', 'ASC')->where('unactive',1)->get();
        $data = [
            "users1"=>$users1,
            "users2"=>$users2,
        ];

        return view('admin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
    {
        $attributes = $request->all();
        $attributes['password'] = bcrypt(env('DEFAULT_USER_PWD'));
        User::create($attributes);

        return redirect()->route('admin.index');
    }
    public function updateUser(Request $request,User $user)
    {
        $user->update($request->all());

        return redirect()->route('admin.index');
    }
    public function resetUser(User $user)
    {
        $attributes['password'] = bcrypt(env('DEFAULT_USER_PWD'));
        $user->update($attributes);

        return redirect()->route('admin.index');
    }
    public function unactiveUser(User $user)
    {
        //$user->delete();
        $att['unactive'] = 1;
        $att['password'] = "YouWereBeUnactiveByAdmin";
        $user->update($att);
        return redirect()->route('admin.index');
    }

    public function activeUser(User $user)
    {
        $att['unactive'] = null;
        $att['password'] = bcrypt(env('DEFAULT_USER_PWD'));
        $user->update($att);
        return redirect()->route('admin.index');
    }
    public function postAdmin()
    {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(20);

        $categories = Category::orderBy('id', 'ASC')->get();

        $data = compact('categories','posts');

        return view('admin.postAdmin',$data);
    }

    public function postDel(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.postAdmin');
    }

    public function storeCategory(Request $request)
    {
        Category::create($request->all());
        return redirect()->route('admin.postAdmin');
    }
    public function reportAdmin()
    {
        $reports = Report::orderBy('created_at', 'DESC')->paginate(20);

        $data = compact('reports');

        return view('admin.reportAdmin',$data);
    }
    public function reportDel(Report $report)
    {

        //先刪附檔
        $mfiles = $report->mfiles;
        foreach($mfiles as $mfile){
            $mfile->delete();

            $filename = str_replace("&","/",$mfile->name);

            $realFile = "../storage/app/public/reports/".$filename;

            unlink($realFile);

        }



        $report->delete();
        return redirect()->route('admin.reportAdmin');
    }


}
