<?php

namespace App\Http\Controllers;

use App\Block;
use App\Models\Post;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    public function index()
    {
        $category_id = Input::get('category_id');
        if($category_id){
            $posts = Post::orderBy('published_at', 'DESC')->orderBy('created_at', 'DESC')->where('category_id',$category_id)->paginate(10);
        }else {
            $posts = Post::orderBy('published_at', 'DESC')->orderBy('created_at', 'DESC')->paginate(10);
        }

        //區塊
        $blockLs = Block::orderBy('id','ASC')->where('style','L')->get();
        $blockRs = Block::orderBy('id','ASC')->where('style','R')->get();
        $blockDs = Block::orderBy('id','ASC')->where('style','D')->get();

        $data = compact('posts','blockLs','blockRs','blockDs');


        return view('home.index',$data);
    }
}
