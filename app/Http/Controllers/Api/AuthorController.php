<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use App\Models\User;
use Response;
use Validator;
use Illuminate\Support\Str;
use Auth;

class AuthorController extends Controller
{
    // show all the users
    public function index()
    {
        return AuthorResource::collection(User::orderyBy('id', 'DESC')->paginate(20));
    }

    // check name validation
    public function checkName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        return Response::json([
            'errors' => $validator->getMessageBag()->toArray(),
        ]);
    }

    //check email validation
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);
        return Response::json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    //check password validation
    public function checkPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:3',
        ]);

        return Response::json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    //register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()->toArray()]);
        } else {
            $author = new User();
            $author->name = $request->name;
            $author->email = $request->email;
            $author->password = bcrypt($request->password);
            $author->api_token = Str::random(80);
            $author->save();

            return Response::json(['success' => 'Registration done successfully', 'author' => $author]);
        }
    }

    //login user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()->toArray()]);
        } else {
            if (Auth::attemp(['email' => $request->email, 'password' => $request->password])) {
                $author = $request->user();
                $author->api_token = Str::random(80);
                $author->save();

                return Response::json([
                    'loggedin' => true,
                    'success' => 'Login was successful',
                    'author' => Auth::user(),
                ]);
            } else {
                return Respose::json([
                    'loggedin' => false,
                    'errors' => 'Login failed ! Wrong credentials.',
                ]);
            }
        }
    }

    //get authenticated author
    public function getAuthor()
    {
        $author = [];
        $author['name'] = Auth::user()->name;
        $author['email'] = Auth::user()->email;
        return Response::json($author);
    }

    // user logged
    public function logout(Request $request)
    {
        $author = $request->user();
        $author->api_token = null;
        $author->save();

        return Response::json(['message' => 'Logged Out']);
    }
}
