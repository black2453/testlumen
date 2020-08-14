<?php


namespace App\Service;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function checkUserAccount($account)
    {
        if (User::query()->where('account', $account)->exists()) {
            return false;
        }

        return true;
    }

    public function addUser($account, $password)
    {
        $user = User::create(
            [
                'account' => $account,
                'password' => Hash::make($password),
                'is_auth' => '1'
            ]
        );

        return $user;
    }

    public function login($account, $password)
    {
        $user = User::query()->where('account', $account)->first();

        if (!$user) {
            return false;
        }

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        $token = $this->setToken();

        User::query()->where('id', $user->id)->update(
            [
                'token' => $token,
                'last_login_time' => Carbon::now()->toDateTimeString()
            ]
        );

        return $token;
    }

    public function loginWithToken($token)
    {
        $user = User::query()->where('token', $token)->first();

        if (!$user) {
            return false;
        }

        $token = $this->setToken();

        User::query()->where('id', $user->id)->update(
            [
                'token' => $token,
                'last_login_time' => Carbon::now()->toDateTimeString()
            ]
        );

        return $token;
    }

    public function checkUserTokenExist($token)
    {
        if (User::query()->where('token', $token)->exists()) {
            return true;
        }
        return false;
    }

    public function getUserIdWithToken($token)
    {
        return User::query()->where('token', $token)->value('id');
    }

    public function getUserInfo($token)
    {
        return User::query()->select('account', 'is_auth', 'token', 'last_login_time')->where('token', $token)->first();
    }

    private function setToken()
    {
        return Str::random(60);
    }
}
