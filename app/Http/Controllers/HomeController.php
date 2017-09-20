<?php

namespace App\Http\Controllers;

use App\Block;
use App\Models\Post;
use Illuminate\Support\Facades\Input;
use Exception;


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


    public function generateDocx()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        $section->addImage("http://itsolutionstuff.com/frontTheme/images/logo.png");
        $section->addText($description);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }

        return response()->download(storage_path('helloWorld.docx'));
    }
}
