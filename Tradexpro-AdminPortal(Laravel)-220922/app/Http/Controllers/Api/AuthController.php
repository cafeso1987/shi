<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmailVerifyRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\SignUpRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Services\AuthService;
use App\Http\Services\Logger;
use App\Http\Services\MyCommonService;
use App\Model\UserVerificationCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public $service;
    public $myCommonService;
    public $logger;
    public function __construct()
    {
        $this->service = new AuthService;
        $this->myCommonService = new MyCommonService;
        $this->logger = new Logger();
    }
    // sign up api
    public function signUp(SignUpRequest $request)
    {
        try {
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                $response = ['success' => false, 'message' => __('Invalid email address'), 'data' =>(object)[]];
                return response()->json($response);
            }
            $result = $this->service->signUpProcess($request);
            return response()->json($result);
        } catch (\Exception $e) {
            $this->logger->log('signUp', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>(object)[]];
            return response()->json($response);
        }
    }

    // verify email
    public function verifyEmail(EmailVerifyRequest $request)
    {
        try {
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                $response = ['success' => false, 'message' => __('Invalid email address'), 'data' =>(object)[]];
                return response()->json($response);
            }
            $result = $this->service->verifyEmailProcess($request);
            return response()->json($result);
        } catch (\Exception $e) {
            $this->logger->log('verifyEmail', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>(object)[]];
            return response()->json($response);
        }
    }

    // login process
    public function signIn(LoginRequest $request)
    {
        try {
            $data['success'] = false;
            $data['message'] = '';
            $data['user'] = (object)[];
            $user = User::where('email', $request->email)->first();

            if (!empty($user)) {
                if($user->role == USER_ROLE_USER) {
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                        $token = $user->createToken($request->email)->accessToken;
                        //Check email verification
                        if ($user->status == STATUS_SUCCESS) {
                            if (!empty($user->is_verified)) {
                                if ($user->g2f_enabled == STATUS_ACTIVE) {
                                    $data['success'] = true;
                                    $data['message'] = __('Please verify two factor authentication to get access ');
                                    $data['email_verified'] = $user->is_verified;
                                    $data['g2f_enabled'] = $user->g2f_enabled;
                                    $data['user'] = $user;
                                    $data['user']->photo = show_image_path($user->photo,IMG_USER_PATH);
                                    createUserActivity(Auth::user()->id, USER_ACTIVITY_LOGIN);
                                    $this->myCommonService->sendNotificationToUserUsingSocket(Auth::user()->id,'Log In','You are successfully logged in!');
                                } else {
                                    $data['success'] = true;
                                    $data['message'] = __('Login successful');
                                    $data['email_verified'] = $user->is_verified;
                                    $data['access_token'] = $token;
                                    $data['access_type'] = 'Bearer';
                                    $data['user'] = $user;
                                    $data['user']->photo = show_image_path($user->photo,IMG_USER_PATH);
                                    createUserActivity(Auth::user()->id, USER_ACTIVITY_LOGIN);
                                    $this->myCommonService->sendNotificationToUserUsingSocket(Auth::user()->id,'Log In','You are successfully logged in!');
                                }

                                return response()->json($data);
                            } else {
                                $existsToken = User::join('user_verification_codes','user_verification_codes.user_id','users.id')
                                    ->where('user_verification_codes.user_id',$user->id)
                                    ->whereDate('user_verification_codes.expired_at' ,'>=', Carbon::now()->format('Y-m-d'))
                                    ->first();
                                if(!empty($existsToken)) {
                                    $mail_key = $existsToken->code;
                                } else {
                                    $mail_key = randomNumber(6);
                                    UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);
                                }
                                try {
                                    $data['email_verified'] = $user->is_verified;
                                    $this->service ->sendVerifyemail($user, $mail_key);
                                    $data['success'] = false;
                                    $data['message'] = __('Your email is not verified yet. Please verify your mail.');
                                    Auth::logout();

                                    return response()->json($data);
                                } catch (\Exception $e) {
                                    $data['email_verified'] = $user->is_verified;
                                    $data['success'] = false;
                                    $data['message'] = $e->getMessage();
                                    Auth::logout();

                                    return response()->json($data);
                                }
                            }
                        } elseif ($user->status == STATUS_SUSPENDED) {
                            $data['email_verified'] = $user->is_verified;
                            $data['success'] = false;
                            $data['message'] = __("Your account has been suspended. please contact support team to active again");
                            Auth::logout();
                            return response()->json($data);
                        } elseif ($user->status == STATUS_DELETED) {
                            $data['email_verified'] = $user->is_verified;
                            $data['success'] = false;
                            $data['message'] = __("Your account has been deleted. please contact support team to active again");
                            Auth::logout();
                            return response()->json($data);
                        } elseif ($user->status == STATUS_PENDING) {
                            $data['email_verified'] = $user->is_verified;
                            $data['success'] = false;
                            $data['message'] = __("Your account has been pending for admin approval. please contact support team to active again");
                            Auth::logout();
                            return response()->json($data);
                        }
                    } else {
                        $data['success'] = false;
                        $data['message'] = __("Email or Password doesn't match");
                        return response()->json($data);
                    }
                } else {
                    $data['success'] = false;
                    $data['message'] = __("You have no login access");
                    Auth::logout();
                    return response()->json($data);
                }
            } else {
                $data['success'] = false;
                $data['message'] = __("You have no account,please register new account");
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $this->logger->log('signIn', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>[]];
            return response()->json($response);
        }

    }

    // forgot password
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $response = $this->service->sendForgotMailProcess($request);
            return response()->json($response);
        } catch (\Exception $e) {
            $this->logger->log('forgotPassword', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>(object)[]];
            return response()->json($response);
        }
    }

    // reset password
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $response = $this->service->passwordResetProcess($request);
            return response()->json($response);
        } catch (\Exception $e) {
            $this->logger->log('resetPassword', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>(object)[]];
            return response()->json($response);
        }
    }

    // verify g2fa code
    public function g2fVerify(Request $request)
    {
        try {
            $response = $this->service->g2fVerifyProcess($request);
            return response()->json($response);
        } catch (\Exception $e) {
            $this->logger->log('g2fVerify', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong'), 'data' =>(object)[]];
            return response()->json($response);
        }
    }

    public function logOutApp()
    {
        Session::forget('g2f_checked');
        Session::flush();
        Cookie::queue(Cookie::forget('accesstokenvalue'));
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => true, 'data' => [], 'message' => __('Logout successfully!')]);
    }
}
