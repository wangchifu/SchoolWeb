<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Pfile;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Input;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->except('index', 'show','downloadPfile');
    }

    public function index()
    {

        $category_id = Input::get('category_id');
        $who_do = Input::get('who_do');
        if($category_id){
            $posts = Post::orderBy('created_at', 'DESC')->where('category_id',$category_id)->paginate(20);
        }elseif($who_do){
            $posts = Post::orderBy('created_at', 'DESC')->where('who_do',$who_do)->paginate(20);
        }else {
            $posts = Post::orderBy('created_at', 'DESC')->paginate(20);
        }
        $data = compact('posts');

        return view('posts.index', $data);
    }

    public function create()
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();

        $data = compact('categories');

        return view('posts.create', $data);
    }

    public function store(PostRequest $request)
    {
        $attributes = $request->all();
        $attributes['user_id'] = auth()->user()->id;
        $attributes['who_do'] = auth()->user()->job_title;

        //如果沒有填unpublished_at，一年後下架
        if($attributes['unpublished_at']==null) {
            $published = explode('-',$request->input('published_at'));
            $attributes['unpublished_at'] = $published[0] + 1 . "-" . $published[1] . "-" . $published[2] . "-" . $published[3];
        }
        $post = Post::create($attributes);

        //處理檔案上傳
        $att['post_id'] = $post->id;
        if ($request->hasFile('upload')) {
            $files = $request->file('upload');
            $folder = 'posts/'.date('Ymd');
            foreach($files as $file)
            {
               $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                    ];
                if ($info['size'] > 5100000)
                {

                } else {
                    $filename = $info['original_filename'];
                    $file->storeAs('public/' . $folder, $filename);
                    $att['name'] = date('Ymd') . '&' . $filename;
                    Pfile::create($att);
                }
            }
        }
        /**

        if ($request->hasFile('upload')) {
            $upload = $request->file('upload');
            $folder = 'posts';
            $filename = str_random(25);
            $extension = $upload->getClientOriginalExtension();
            $upload->storeAs('public/'.$folder, $filename.'.'.$extension);
            $attributes['file'] = $folder.'/'.$filename.'.'.$extension;
        }
        */

        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        if($_SERVER['REMOTE_ADDR'] == "163.23.93.126"){
            $client_in = "1";
        }else{
            $client_in = "0";
        }
        //校內文件不許校外看
        if($client_in=="0" and $post->insite==1){
            return redirect()->route('posts.index');
        }


        $s_key = "pv".$post->id;
        if(!session($s_key)){
            $att['page_view'] = $post->page_view+1;
            $post->update($att);
        }
        session([$s_key => '1']);



        $data = compact('post');

        return view('posts.show', $data);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $categories = Category::all()->pluck('name', 'id')->toArray();

        $data = compact('post', 'categories');

        return view('posts.edit', $data);
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->all());


        //處理檔案上傳
        $att['post_id'] = $post->id;
        if ($request->hasFile('upload')) {
            $files = $request->file('upload');
            $folder = 'posts/'.date('Ymd');
            foreach($files as $file)
            {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                if ($info['size'] > 5100000)
                {

                } else {
                    $filename = $info['original_filename'];
                    $file->storeAs('public/' . $folder, $filename);
                    $att['name'] = date('Ymd') . '&' . $filename;
                    Pfile::create($att);
                }
            }
        }


        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index');
    }
    public function downloadPfile($downloadPfile)
    {
        if ($downloadPfile) {
            $downloadPfile = str_replace("&","/",$downloadPfile);
            $filename = explode('/',$downloadPfile);
            $realFile = "../storage/app/public/posts/".$downloadPfile;
            header("Content-type:application");
            header("Content-Length: " .(string)(filesize($realFile)));
            header("Content-Disposition: attachment; filename=".$filename[1]);
            readfile($realFile);
        } else {
            return null;
        }
    }
    public function delPfile(Pfile $pfile)
    {
        $post_id = $pfile->post_id;
        $filename = str_replace("&","/",$pfile->name);

        $realFile = "../storage/app/public/posts/".$filename;

        unlink($realFile);

        $pfile->delete();



        $data = [
            'post_id' => $post_id,
        ];
        return redirect()->route('posts.edit',$data);
    }
}
