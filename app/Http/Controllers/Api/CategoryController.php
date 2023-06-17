<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Response;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\Rule;
use App\Models\Category;

class CategoryController extends Controller
{
    // show all the categories
    public function index(){
        return CategoryResource::collection(Category::orderBy('id', "DESC")->paginate(10));
    }

    //store new category into the databse
    public function store(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required'
        ]);
        if($validators->fails())
        {
            return Response::json([
                'errors' => $validators->getMessageBag()->toArray()
            ]);
        }else {
            $category = new Category();
            $category->title = $request->title;
            $category->slug=strtolower(implode('-',explode(' ',$request->slug)));
            $category->save();

            return Response::json(['success'=>'Category created successfully']);
        }
    }

    //show a specific category by id
    public function show($id)
    {
        if(Category::where('id', $id)->first()){
            return new CategoryResource(Category::findOrFail($id));
        }else {
            return Response::json(['error' => 'Category not found']);
        }
    }

    public function update(Request $request, $id)
    {
        $validators= Validator::make($request->all(), [
            'title' => ['required'],
            'slug' => ['required'],
        ]);

        if($validators->fails()){
            return Response::json(['errors' => $validators->getMessageBag()->toArray()]);
        }else{
            $category= Category::findOrFail($id);
            $category->title =$request->title;
            $category->slug= strtolower(implode('-',explode(' ', $request->slug)));
            $category->save();

            return Response::json([
                'success'=>'Category updated successfully'
            ]);
        }
    }

     // remove category using id
     public function remove(Request $request){
        try{
            $category=Category::where('id',$request->id)->first();
            if($category){
                $category->delete();
                return Response::json(['success'=>'Category removed successfully !']);
            }else{
                return Response::json(['error'=>'Category not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Category belongs to an article.So you cann\'t delete this category!']);
        }
    }

    // search category by keyword
    public function searchCategory(Request $request){
        $categories=Category::where('title','LIKE','%'.$request->keyword.'%')->orWhere('slug','LIKE','%'.$request->keyword.'%')->get();
        if(count($categories)==0){
            return Response::json(['message'=>'No category match found !']);
        }else{
            return Response::json($categories);
        }
    }
}
