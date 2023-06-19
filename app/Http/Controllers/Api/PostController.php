<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Comment;
use Auth;
use Illuminate\Support\Str;
class PostController extends Controller
{
    public function index(){
        return PostResource::collection(User::where('author_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10));
    }

    // store new post into the database
    public function store(Request $request){
        $validators = Validator::make($request->all(), [
            'title' => 'required',
            'category' => 'required',
            'body' => 'required',
        ]);

        if($validators->fails()){
            return Response::json(['errors'=> $validators->getMessageBag()->toArray()]);
        }else {
            $post = new Post();
            $post->title =$request->title;
            $post->author_id =Auth::user()->id;
            $post->category_id =$request->category;
            $post->body =$request->body;

            if($request->file('image') == NULL)
            {
                $post->image = 'placeholder.png';;
            }else {
                $filename=Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $post->image=$filename;
                $request->image->move(public_path('images'),$filename);
            }
            $post->save();
            return Response::json(['success'=>'Post created successfully !']);
        }
    }
}
