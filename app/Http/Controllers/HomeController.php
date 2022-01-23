<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Events\NewNotification;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $posts = Post::get();

        return view('home', compact('posts'));
    }

    public function  saveComment(Request $request)
    {

        Comment::create([
               'post_id' => $request->post_id ,
               'user_id' => Auth::id(),
               'comment' => $request->comment,

        ]);

        $data =[
            'user_id' => Auth::id(),
            'user_name'  => Auth::user()->name,
            'comment' => $request->comment,
            'post_id' =>$request->post_id ,
        ];

        ///   save  notify in database table ////

        event(new NewNotification($data));

        return redirect()->back()->with(['success'=> 'تم اضافه تعليقك بنجاح ']);

    }

}
