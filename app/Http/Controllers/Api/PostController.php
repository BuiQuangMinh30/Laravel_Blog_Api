<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use Auth;
use Illuminate\Support\Str;
class PostController extends Controller
{
    public function index(){
        return PostResource::collection(Post::orderBy('id', 'DESC')->paginate(10));
    }

    // store new post into the database
    public function store(Request $request){
        $validators = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'body' => 'required',
        ]);

        if($validators->fails()){
            return Response::json(['errors'=> $validators->getMessageBag()->toArray()]);
        }else {
            $post = new Post();
            $post->title =$request->title;
            $post->author_id =Auth::user()->id;
            $post->category_id =$request->category_id;
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

    // show a specific article by id
    public function show($id){
        if(Post::where('id', $id)->first()){
            return new PostResource(Post::findOrFail($id));
        }else {
            return Response::json([
                'error' => 'Post not found'
            ]);
        }
    }

    // update post using id
    public function update(Request $request,  $id)
    {
        $validators = Validator::make($request->all(), [
            'title'=> 'required',
            'body'=> 'required',
            'category_id' => 'required',
        ]);

        if($validators->fails()){
            return Response::json([
                'errors' => $validators->getMessageBag()->toArray()
            ]);
        }else {
            $post = Post::where('id',$id)->where('author_id', Auth::user()->id)->first();
            if($post){
                $post->title = $request->title;
                $post->author_id = Auth::user()->id;
                $post->category_id = $request->category_id;
                $post->body = $request->body;

                if($request->file('image' == NULL)){
                    $post->image = 'placeholder.png';
                }else {
                    $filename = Str::random(20) . '.' .$request->file('image')->getClientOriginalExtension();
                    $post->image = $filename;
                    $request->image->move(public_path('images'), $filename);
                }
                $post->save();
                return Response::json([
                    'success'=> 'Post updated successfully'
                ]);
            }else {
                return Response::json([
                    'error' => 'Post not found'
                ]);
            }
        }
    }

    //remove post using id
    public function remove(Request $request)
    {
        try {
            $post = Post::where('id', $request->id)->where('author_id', Auth::user()->id)->first();
            if($post)
            {
                $post->delete();
                return Response::json([
                    'success' => 'Post deleted successfully'
                ]);
            }else {
                return Response::json([
                    'error' => 'Post deleted failed'
                ]);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Post belongs to comment.So you cann\'t delete this post!']);
        }
    }

    // search post by keywords
    public function searchPost(Request $request)
    {
        $post = Post::where('title', 'LIKE', '%'.$request->keyword.'%')->get();
        if(count($post) == 0){
            return Response::json([
                'messages' => 'No post found'
            ]);
        }else {
            return Response::json($post);
        }
    }

    //fetch commetns to posts
    public function comments($id){
        if(Article::where('id',$id)->first()){
            return CommentResource::collection(Comment::where('article_id',$id)->get());
        }else{
            return Response::json(['error'=>'Article not found!']);
        }
    }
}
