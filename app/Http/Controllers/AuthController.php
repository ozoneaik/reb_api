<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\login_history;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    // register a new user method
    public function register(RegisterRequest $request) {

        $data = $request->validated();

        $user = User::create([
            'prefix' => $data['prefix'],
            'name' => $data['name'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'home_id' => $data['home_id'],
            'mu' => $data['mu'],
            'amphure' => $data['amphure'],
            'tambon' => $data['tambon'],
            'city' => $data['city'],
            'zip_id' => $data['zip_id'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user){
            $history = new login_history();
            $history->user_id = $user->id;
            $history->date = $user->created_at;
            $history->save();

            $details = [
                'title' => 'สมัครสมาชิกสำเร็จ',
                'body' => 'ฮ่าๆๆๆๆ'
            ];
            \Mail::to($user->email)->send(new \App\Mail\MyTestMail($details));
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return response()->json([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    // login a user method
    public function login(LoginRequest $request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email or password is incorrect!'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        if ($user){
            $history = new login_history();
            $history->user_id = $user->id;
            $history->date = Carbon::now();
            $history->save();
        }

        return response()->json([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    // logout a user method
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return response()->json([
            'message' => 'Logged out successfully!'
        ])->withCookie($cookie);
    }

    // get the authenticated user method
    public function user(Request $request) {
        return new UserResource($request->user());
    }

    public function get_history(string $id){
        $users = login_history::select('login_histories.*')
            ->leftJoin('users', 'login_histories.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->get();

        if ($users){
            return $users;
        }

        return response()->json([
            'success' => false
        ]);

    }

    public function UserList(){
        $users = User::all();

        if ($users){
            return $users;
        }

        return response()->json([
            'success' => false
        ]);

    }
}
