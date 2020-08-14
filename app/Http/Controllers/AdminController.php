<?php


namespace App\Http\Controllers;


use App\Service\AdminService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    /**
     * 管理者登入
     * @param Request $request
     * @param AdminService $adminService
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function doLogin(Request $request, AdminService $adminService)
    {
        $account = $request->input('account');
        $password = $request->input('password');

        if ($account != 'admin' || $password != 'admin') {
            $checkLogin = false;
        }else{
            $checkLogin = true;
        }

        if (!$checkLogin) {
            return redirect('/admin/login');
        }

        $adminService->putSession($account);

        if (getSession('account') != $account) {
            return redirect('/admin/login');
        } else {
            return redirect('/admin/index');
        }
    }

    /**
     * 管理者登出
     * @param AdminService $adminService
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function doLogout(AdminService $adminService)
    {
        $adminService->forgetSession();
        return redirect('/admin/login');
    }

    /**
     * 主畫面導引
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('index');
    }
}
