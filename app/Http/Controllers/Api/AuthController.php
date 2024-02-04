<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Validator;
use DB;

class AuthController extends Controller
{

    public function adminAdd(Request $request)
    {
        #write your code for Add Admin here...
		#model name = Users
		#table name = users
		#table fields = id,name,email,password,remember_token,created_at,updated_at
		#all fields are required

        $admin = new User($request->input());

        $admin->save();
        return response()->json($admin);
    }

    public function login()
    {
        #write your code for Admin login here...
        #model name = Users
        #table name = users
        #table fields = id,name,email,password,remember_token,created_at,updated_at
        #all fields are required
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function newsAdd(Request $request)
    {
        #write your code for Admin add news here...
        #model name = News,Users
        #table name = news,user
        #table fields = id,userid,publisher,description,title,clicks,trendRate
        #all fields are required
        $admin =  auth()->user();

        if(!$admin) {
            return response()->json([], 400);
        }

        $news = new News($request->input());
        $news->save();

        return response()->json($news);
	}
}