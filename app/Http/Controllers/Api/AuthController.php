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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ], [
            'required' => 'email or name or password missing'
        ]);

        if ($validator->fails()) {
            return response()->json(['errMsg' => implode($validator->errors()->all())], 400);
        }

        $admin = new User([
            'name' => $request->input()['name'],
            'email' => $request->input()['email'],
            'password' => Hash::make($request->input()['password'])
        ]);
        $admin->save();

        return response()->json($admin, 201);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($token);
    }

    public function newsAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publisher' => 'required',
            'title' => 'required',
            'description' => 'required',
            'clicks' => 'required',
        ], [
            'required' => 'MandatoryFieldsNotComplete'
        ]);

        if ($validator->fails()) {
            return response()->json(['errMsg' => $validator->errors()->all()[0]], 400);
        }

        $admin = auth()->user();
        if(!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $news = new News($request->input());
        $news->userid = $admin->id;
        $news->save();

        return response()->json($news, 201);
	}
}