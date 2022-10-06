<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\AdminCreateUser;
use App\Http\Services\AuthService;
use App\Http\Services\Logger;
use App\Http\Services\MailService;
use App\Model\Coin;
use App\Model\UserVerificationCode;
use App\Model\VerificationDetails;
use App\Model\Wallet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public $service;
    public $logger;
    public function __construct()
    {
        $this->service = new AuthService;
        $this->logger = new Logger;
    }
    // user list
    public function adminUsers(Request $request)
    {
        $data['title'] = __('Users');
        if ( !$request->ajax() ) {
            return view('admin.users.users', $data);
        } else {
            $users = [];
            if ($request->type == 'active_users') {}
            $users = User::where('status', STATUS_SUCCESS)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'suspend_user')
                $users = User::where('status', STATUS_SUSPENDED)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'deleted_user')
                $users = User::where('status', STATUS_DELETED)->where('id', '<>', Auth::user()->id);
            if ($request->type == 'email_pending')
                $users = User::where('is_verified','!=', STATUS_SUCCESS )->where('id', '<>', Auth::user()->id);
            if ($request->type == 'phone_pending')
                $users = User::where('phone_verified','!=', STATUS_SUCCESS)->where('id', '<>', Auth::user()->id);
            return datatables($users)

                ->addColumn('status', function ($item) {
                    return statusAction($item->status);
                })
                ->addColumn('type', function ($item) {
                    return userRole($item->role);
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at ? with(new Carbon($item->created_at))->format('d M Y') : '';
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at,'%d %M %Y') like ?", ["%$keyword%"]);
                })
                ->addColumn('activity', function ($item) use ($request) {
                    return getActionHtml($request->type,$item->id,$item);
                })
                ->rawColumns(['activity', 'status'])
                ->make(true);
        }
    }

    // generate verification key
    private function generate_email_verification_key()
    {
        $key = randomNumber(6);
        return $key;
    }

    // create and edit user
    public function UserAddEdit(AdminCreateUser $request)
    {
        try {
            if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('dismiss', __('Invalid email address'));
            }
            $response = $this->service->addNewUser($request);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->with('dismiss', $response['message']);
            }
        } catch (\Exception $e) {
            $this->logger->log('add new user by admin', $e->getMessage());
            return redirect()->back()->with('dismiss', __("Something went wrong"));
        }

    }

    // user edit page
    public function adminUserProfile(Request $request)
    {
        $data['title'] = __('User Profile');
        $data['user'] = User::find(decrypt($request->id));
        $data['type'] = $request->type;
        $data['clubInfos'] = get_plan_info($data['user']->id);

        return view('admin.users.profile',$data);
    }

    // user edit page
    public function UserEdit(Request $request)
    {
        $data['title'] = __('User Edit');
        $data['user'] = User::find(decrypt($request->id));
        $data['type'] = $request->type;
        return view('admin.users.edit',$data);
    }

    // delete user
    public function adminUserDelete($id)
    {
        $user = User::find(decrypt($id));
        $user->status = STATUS_DELETED;
        $user->save();
        return redirect()->back()->with('success',__('User deleted successfully'));
    }

    // suspend user
    public function adminUserSuspend($id){
        $user = User::find(decrypt($id));
        $user->status = STATUS_SUSPENDED;
        $user->save();
        return redirect()->back()->with('success',__('User suspended successfully'));
    }

    // remove user gauth
    public function adminUserRemoveGauth($id){
        $user = User::find(decrypt($id));
        $user->google2fa_secret = '';
        $user->g2f_enabled  = '0';
        $user->save();
        return redirect()->back()->with('success',__('User gauth removed successfully'));
    }
// verify user phone
    public function adminUserPhoneVerified($id){
        $user = User::find(decrypt($id));
        if (empty($user->phone)) {
            return redirect()->back()->with('dismiss',__('User phone number is empty'));
        }
        $user->phone_verified = STATUS_SUCCESS;
        $user->save();
        return redirect()->back()->with('success',__('Phone verified successfully'));
    }
    // activate user
    public function adminUserActive($id){
        $user = User::find(decrypt($id));
        $user->status = STATUS_SUCCESS;
        $user->save();
        return redirect()->back()->with('success',__('User activated successfully'));
    }

    // verify user email
    public function adminUserEmailVerified($id){
        $user = User::find(decrypt($id));
        $user->is_verified = STATUS_SUCCESS;
        $user->save();
        return redirect()->back()->with('success',__('Email verified successfully'));
    }

    //ID Verification
    public function adminUserIdVerificationPending(Request $request)
    {
        if ($request->ajax()) {
            $data['items'] = VerificationDetails::join('users','users.id','verification_details.user_id')
                ->select('users.id','users.updated_at', 'users.first_name', 'users.last_name', 'users.email')
                ->groupBy('user_id')
                ->where('verification_details.status',STATUS_PENDING)
                ->where(function ($query) {
                });

            return datatables()->of($data['items'])
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser">
                        <a title="'.__('Details').'" href="' . route('adminUserDetails', encrypt($item->id)) . '?tab=photo_id" class="btn btn-info btn-sm">
                        <i class="fa fa-eye"></i>
                        </a></li>
                        </ul>';
                })->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.users.users-pending-id-verification');
    }

    // verification details
    public function VerificationDetails($id){
        $data['user_id'] = $id;
        $data['pending'] = VerificationDetails::where('user_id',decrypt($id))->where('status',STATUS_PENDING)->get();
        $data['fields_name'] = VerificationDetails::where('user_id',decrypt($id))->where('status',STATUS_PENDING)->get()->pluck('id','field_name')->toArray();
        if (!empty($data['pending'])) {
            return view('admin.users.users-pending-id-verification-details',$data);
        }

        return redirect()->route('adminUserIdVerificationPending');
    }

    // activate user verification
    public function adminUserVerificationActive($id,$type)
    {
        try {
            if ($type == 'nid'){
                $verified = ['nid_front','nid_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            } elseif ($type == 'driving'){
                $verified = ['drive_front','drive_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            } elseif ($type == 'passport') {
                $verified = ['pass_front','pass_back'];
                VerificationDetails::where('user_id',decrypt($id))
                    ->whereIn('field_name',$verified)->update(['status'=>STATUS_SUCCESS]);

                return redirect()->route('adminUserIdVerificationPending')->with(['success' => __('Successfully Updated')]);
            }
        } catch (\Exception $exception){
            return redirect()->route('adminUserIdVerificationPending')->with(['dismiss' => __('Something went wrong')]);
        }
    }

    // verification reject process
    public function varificationReject(Request $request){
        try {
            $companyName = env('APP_NAME');
            $data['data'] = User::find(decrypt($request->user_id));
            $data['cause'] = $request->couse;
            $data['email'] = $data['data']->email;
            $user = $data['data'] ;

            $this->sendIdVerificationEmail($data,$user);

            if (isset($request->ids[0])) {
                foreach ($request->ids as $key => $value) {
                    deleteFile(IMG_USER_PATH, $value);
                }
            }
            VerificationDetails::whereIn('photo',$request->ids)->update(['status'=>STATUS_REJECTED, 'photo'=>'']);

            return redirect()->route('adminUserIdVerificationPending')->with('success',__('Rejected successfully'));
        } catch (\Exception $e) {
            $this->logger->log('varificationReject', $e->getMessage());
            return redirect()->back()->with('dismiss',__('Something went wrong'));
        }

    }

    // send rejection mail to user
    public function sendIdVerificationEmail($data,$user)
    {
        try {
            $mailService = new MailService();
            $userName = $user->first_name.' '.$user->last_name;
            $userEmail = $user->email;
            $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
            $subject = __('KYC Verification | :companyName', ['companyName' => $companyName]);
            $mailService->send('email.verification_fields', $data, $userEmail, $userName, $subject);

            return true;
        } catch (\Exception $e) {
            $this->logger->log('sendIdVerificationEmail', $e->getMessage());
        }
    }

}
