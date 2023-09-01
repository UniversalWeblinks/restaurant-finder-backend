<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckUserApi
{
    /**
     * Handle an incoming API request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');
        if (empty($authorization)) {
            return response()->json([
                'msg' => 'Unauthorized',
            ],401);
        }

        $access_token = trim(ltrim($authorization, 'Bearer'));
        $check_user = DB::table('users')->where('access_token', $access_token)
                ->select('id', 'image', 'username', 'uid', 'access_token', 'token_expiry_date')
                ->first();
        
        if (empty($check_user)) {
            return response()->json([
                'msg' => 'User does not exist',
            ],401);
        }

        $expiry_date = $check_user->token_expiry_date;

        if (empty($expiry_date)) {
            return response()->json([
                'msg' => 'Error.. Login again',
            ],401);
        }

        if ($expiry_date < Carbon::now()) {
            return response()->json([
                'msg' => 'Token Expired.. Login again',
            ],401);
        }
        $addTime = Carbon::now()->addDays(5);

        if ($expiry_date < $addTime) {
            $add_expiry_date = Carbon::now()->addDays(30);
            DB::table('users')->where('access_token', $access_token)
                    ->update([
                        'token_expiry_date' => $add_expiry_date,
                        'updated_at' => Carbon::now(),
                    ]);
        }

        $request['user_id'] = $check_user->id;
        $request['username'] = $check_user->username;
        $request['image'] = $check_user->image;
        $request['uid'] = $check_user->uid;
        
        return $next($request);
    }
}
