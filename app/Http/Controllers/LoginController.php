<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mews\Captcha\Captcha;

class LoginController extends BaseController
{
    public function captcha()
    {
        $captcha =Captcha::create('flat', true);
        return Response::ok($captcha);
    }
}
