<?php


namespace App\Http\Controllers;


use App\Service\UserService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class UserController extends BaseController
{
    /**
     * 註冊
     * @param Request $request
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(Request $request, UserService $userService)
    {
        $account = trim($request->account);
        $password = trim($request->password);

        if (!$account) {
            return response()->json(['error' => '帐号不可为空'], 400);
        }

        if (!$password) {
            return response()->json(['error' => '密码不可为空'], 400);
        }

        if (!$userService->checkUserAccount($account)) {
            return response()->json(['error' => '该帐号已注册'], 400);
        }

        if (!preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $account)) {
            return response()->json(['error' => '邮箱格式錯誤'], 400);
        }

        if (!preg_match("/^[A-Za-z0-9]+$/", $password)) {
            return response()->json(['error' => '密码只能包含英文和数字'], 400);
        }

        if (strlen($password) < 8 || strlen($password) > 20) {
            return response()->json(['error' => '密码长度需再8码到20码之间'], 400);
        }
        $userData = $userService->addUser($account, $password);
        //  信箱驗證預留

        if (!$userData) {
            return response()->json(['error' => '注册失敗']);
        }

        return response()->json(['code' => 0, 'msg' => '注册成功']);
    }

    /**
     * 登入
     * @param Request $request
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, UserService $userService)
    {
        $account = trim($request->account);
        $password = trim($request->password);

        if (!$account) {
            return response()->json(['error' => '帐号不可为空'], 400);
        }

        if (!$password) {
            return response()->json(['error' => '密码不可为空'], 400);
        }

        $token = $userService->login($account, $password);

        if (!$token) {
            return response()->json(['error' => '帐号或密码输入错误'], 400);
        }

        return response()->json(['code' => 0, 'msg' => '登入成功', 'token' => $token]);
    }

    public function loginWithToken(Request $request, UserService $userService)
    {
        $token = $request->token;

        $newToken = $userService->loginWithToken($token);

        if (!$newToken) {
            return response()->json(['error' => '登入失敗，請重新登入'], 403);
        }

        return response()->json(['code' => 0, 'msg' => '登入成功', 'token' => $newToken]);
    }

    public function getUserInfo(Request $request, UserService $userService)
    {
        $token = $request->token;

        if (!$token) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($token)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        $userInfo = $userService->getUserInfo($token);

        return response()->json(
            ['code' => 0, 'msg' => '成功', 'data' => $userInfo]
        );
    }

    public function send()
    {
        $this->authEmail();
    }

    private function authEmail()
    {
        $mailer = new PHPMailer;
//        $mailer = new PHPMailer();
        try {
            //Server settings
//            $mailer->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mailer->isSMTP();
            $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $mailer->Host = '192.168.33.1';
            $mailer->Port = 25;
            $mailer->setFrom('from@example.com', 'First Last');
//Set an alternative reply-to address
//            $mailer->addReplyTo('replyto@example.com', 'First Last');

            $mailer->addAddress('black@bowei.page', 'test0000');     // Add a recipient
//            $mailer->addAddress('ellen@example.com');               // Name is optional
//            $mailer->addReplyTo('info@example.com', 'Information');
//            $mailer->addCC('cc@example.com');
//            $mailer->addBCC('bcc@example.com');

            // Attachments
//            $mailer->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mailer->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mailer->isHTML(true);                                  // Set email format to HTML
            $mailer->Subject = 'Here is the subject';
            $mailer->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}";
        }
    }
}
