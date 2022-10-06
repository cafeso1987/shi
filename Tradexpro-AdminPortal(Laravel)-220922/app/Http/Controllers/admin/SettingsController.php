<?php

namespace App\Http\Controllers\admin;

use App\Http\Repositories\SettingRepository;
use App\Http\Services\MailService;
use App\Model\AdminSetting;
use App\Model\ContactUs;
use App\Model\CustomPage;
use App\Model\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    private $settingRepo;
    public function __construct()
    {
        $this->settingRepo = new SettingRepository();
    }

// admin setting
    public function adminSettings(Request $request)
    {
        $data['tab']='general';
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }
        $data['title'] = __('General Settings');
        $data['settings'] = allsetting();

        return view('admin.settings.general', $data);
    }

// admin coin api setting
    public function adminCoinApiSettings(Request $request)
    {
        $data['tab']='payment';
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }
        $data['title'] = __('Coin Api Settings');
        $data['settings'] = allsetting();

        return view('admin.settings.api.general', $data);
    }

    // admin feature setting
    public function adminFeatureSettings(Request $request)
    {
        $data['tab']='co-wallet';
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }
        $data['title'] = __('Feature Settings');
        $data['settings'] = allsetting();

        return view('admin.settings.feature', $data);
    }

    // feature save process
    public function saveAdminFeatureSettings(Request $request)
    {
        $rules = [
            MAX_CO_WALLET_USER_SLUG => 'required|integer',
            CO_WALLET_WITHDRAWAL_USER_APPROVAL_PERCENTAGE_SLUG => 'required|numeric'
        ];
        $this->validate($request, $rules);
        $coWalletActive = $request->co_wallet_feature_active ?? 0;
        AdminSetting::updateOrCreate(['slug'=>CO_WALLET_FEATURE_ACTIVE_SLUG], ['value'=> $coWalletActive]);
        $response = $this->saveAllAdminSettingsDynamicallyFromRequest($request, ['_token', 'itech', CO_WALLET_FEATURE_ACTIVE_SLUG]);
        if ($response['success'] == true) {
            return redirect()->route('adminFeatureSettings', ['tab' => 'co-wallet'])->with('success', $response['message']);
        } else {
            return redirect()->route('adminFeatureSettings', ['tab' => 'co-wallet'])->withInput()->with('success', $response['message']);
        }
    }

    // admin setting dynamically save process
    private function saveAllAdminSettingsDynamicallyFromRequest($request, $except): array
    {
        try {
            DB::beginTransaction();
            foreach ($request->except($except) as $key => $value) {
                AdminSetting::updateOrCreate(['slug'=>$key], ['value'=>$value]);
            }
            DB::commit();
            return ['success'=> true, 'message' => __('Saved successfully.')];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['success'=> false, 'message'=>__('Something went wrong.')];
        }

    }

    // admin common settings save process
    public function adminCommonSettings(Request $request)
    {
        $rules=[
            'company_name' => 'required',
            'exchange_url' => 'required',
        ];
//        $messages=[];
        if(!empty($request->logo)){
            $rules['logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->favicon)){
            $rules['favicon']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->login_logo)){
            $rules['login_logo']='image|mimes:jpg,jpeg,png|max:2000';
        }
        if(!empty($request->coin_price)){
            $rules['coin_price']='numeric';
        }
        if(!empty($request->number_of_confirmation)){
            $rules['number_of_confirmation']='integer';
        }
        if(!empty($request->trading_price_tolerance)){
            $rules['trading_price_tolerance']='numeric';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;

            return redirect()->route('adminSettings', ['tab' => 'general'])->with(['dismiss' => $errors[0]]);
        }
        try {
            if ($request->post()) {
                $response = $this->settingRepo->saveCommonSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'general'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'general'])->withInput()->with('success', $response['message']);
                }
            }
        } catch(\Exception $e) {
            storeException('adminCommonSettings', $e->getMessage());
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
        return back();
    }

    // admin email setting save
    public function adminSaveEmailSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'mail_host' => 'required'
                ,'mail_port' => 'required'
                ,'mail_username' => 'required'
                ,'mail_password' => 'required'
                ,'mail_encryption' => 'required'
                ,'mail_from_address' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'email'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveEmailSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'email'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'email'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin twillo setting save
    public function adminSaveSmsSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'twillo_secret_key' => 'required'
                ,'twillo_auth_token' => 'required'
                ,'twillo_number' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'sms'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveTwilloSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'sms'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'sms'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }


    // admin referral setting save
    public function adminReferralFeesSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [];
            if($request->fees_level1) {
                $rules['fees_level1'] = 'numeric|min:0|max:100';
            }
            if($request->fees_level2) {
                $rules['fees_level2'] = 'numeric|min:0|max:100';
            }
            if($request->fees_level3) {
                $rules['fees_level3'] = 'numeric|min:0|max:100';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'referral'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveReferralSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'referral'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'referral'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin withdrawal setting save
    public function adminWithdrawalSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'minimum_withdrawal_amount' => 'required|numeric',
                'maximum_withdrawal_amount' => 'required|numeric',
                'max_send_limit' => 'required|numeric',
                'send_fees_type' => 'required|numeric',
                'send_fees_fixed' => 'required|numeric',
                'send_fees_percentage' => 'required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'withdraw'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveWithdrawSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'withdraw'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'withdraw'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin referral setting save
    public function adminSavePaymentSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'COIN_PAYMENT_PUBLIC_KEY' => 'required',
                'COIN_PAYMENT_PRIVATE_KEY' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminCoinApiSettings', ['tab' => 'payment'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->savePaymentSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'payment'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'payment'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin captcha setting save
    public function adminCapchaSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'google_recapcha' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'capcha'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveCapchaSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'capcha'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'capcha'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }


    // admin node setting save
    public function adminNodeSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'coin_api_user' => 'required',
                'coin_api_pass' => 'required',
                'coin_api_host' => 'required',
                'coin_api_port' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminSettings', ['tab' => 'node'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveNodeSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminSettings', ['tab' => 'node'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminSettings', ['tab' => 'node'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }


    //Contact Us Email List
    public function contactEmailList(Request $request)
    {
            $items = ContactUs::select('*');
            return datatables($items)
                ->addColumn('details', function ($item) {
                    $html = '<button class="btn btn-info show_details" data-toggle="modal" data-target="#descriptionModal" data-id="'.$item->id.'">Details</button>';
                    return $html;
                })
                ->removeColumn(['created_at', 'updated_at'])
                ->rawColumns(['details'])
                ->make(true);
    }

    public function getDescriptionByID(Request $request){
        $response = ContactUs::where('id', $request->id)->first();
        return response()->json($response);
    }

    // Faq List
    public function adminFaqList(Request $request)
    {
        $data['title'] = __('FAQs');
        if ($request->ajax()) {
            $data['items'] = Faq::orderBy('id', 'desc');
            return datatables()->of($data['items'])
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminFaqEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> </li>
                        <li class="deleteuser"><a href="' . route('adminFaqDelete', $item->id) . '"><i class="fa fa-trash"></i></a></li>
                        </ul>';
                })
                ->rawColumns(['actions','status'])
                ->make(true);
        }

        return view('admin.faq.list', $data);
    }

    // View Add new faq page
    public function adminFaqAdd(){
        $data['title']=__('Add FAQs');
        return view('admin.faq.addEdit',$data);
    }

    // Create New faq
    public function adminFaqSave(Request $request){
        $rules=[
            'question'=>'required',
            'answer'=>'required',
            'status'=>'required',
        ];
        $messages = [
            'question.required' => __('Question field can not be empty'),
            'answer.required' => __('Answer field can not be empty'),
            'status.required' => __('Status field can not be empty'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            return redirect()->back()->withInput()->with(['dismiss' => $errors[0]]);
        }

        $data=[
            'question'=>$request->question
            ,'answer'=>$request->answer
            ,'status'=>$request->status
            ,'author'=>Auth::id()
        ];
        if(!empty($request->edit_id)){
            Faq::where(['id'=>$request->edit_id])->update($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Updated Successfully!')]);
        }else{
            Faq::create($data);
            return redirect()->route('adminFaqList')->with(['success'=>__('Faq Added Successfully!')]);
        }
    }

    // Edit Faqs
    public function adminFaqEdit($id){
        $data['title']=__('Update FAQs');
        $data['item']=Faq::findOrFail($id);

        return view('admin.faq.addEdit',$data);
    }

    // Delete Faqs
    public function adminFaqDelete($id){

        if(isset($id)){
            Faq::where(['id'=>$id])->delete();
        }

        return redirect()->back()->with(['success'=>__('Deleted Successfully!')]);
    }

    // admin payment setting
    public function adminPaymentSetting()
    {
        $data['title'] = __('Payment Method');
        $data['settings'] = allsetting();
        $data['payment_methods'] = paymentMethods();

        return view('admin.settings.payment-method', $data);
    }

    // chnage payment method status
    public function changePaymentMethodStatus(Request $request)
    {
        $settings = allsetting();
        if (!empty($request->active_id)) {
            $value = 1;
            $item = isset($settings[$request->active_id]) ? $settings[$request->active_id] : 2;
            if ($item == 1) {
                $value = 2;
            } elseif ($item == 2) {
                $value = 1;
            }
            AdminSetting::updateOrCreate(['slug' => $request->active_id], ['value' => $value]);
        }
        return response()->json(['message'=>__('Status changed successfully')]);
    }


    // admin node setting save
    public function adminSaveBitgoSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'bitgo_api' => 'required|max:255',
                'bitgoExpess' => 'required|max:255',
                'BITGO_ENV' => 'required|max:255|in:test,live',
                'bitgo_token' => 'required|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminCoinApiSettings', ['tab' => 'bitgo'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveBitgoSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'bitgo'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'bitgo'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin api setting save
    public function adminSaveOtherApiSettings(Request $request)
    {
        if ($request->post()) {
            try {
                $response = $this->settingRepo->saveAdminSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'crypto'])->with('success', $response['message']);
                } else {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'crypto'])->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin node setting save
    public function adminSaveERC20ApiSettings(Request $request)
    {
        if ($request->post()) {
            $rules = [
                'erc20_app_url' => 'required|max:255',
                'erc20_app_key' => 'required|max:255',
                'erc20_app_port' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;
                return redirect()->route('adminCoinApiSettings', ['tab' => 'erc20'])->with(['dismiss' => $errors[0]]);
            }

            try {
                $response = $this->settingRepo->saveAdminSetting($request);
                if ($response['success'] == true) {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'erc20'])->with('success', __('Api setting updated successfully'));
                } else {
                    return redirect()->route('adminCoinApiSettings', ['tab' => 'erc20'])->withInput()->with('success', __('Api setting updated successfully'));
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }
        }
    }

    // admin cookie settings
    public function adminCookieSettings()
    {
        $data['title'] = __('Cookie Settings');
        $data['settings'] = allsetting();
        $data['pages'] = CustomPage::where(['status' => STATUS_ACTIVE])->get();

        return view('admin.settings.cookies.cookie_settings', $data);
    }
    // admin chat api settings
    public function adminChatSettings()
    {
        $data['title'] = __('Chat Api Settings');
        $data['settings'] = allsetting();

        return view('admin.settings.chat-api.chat_api_settings', $data);
    }

    // save cookie settings
    public function adminCookieSettingsSave(Request $request)
    {
        try {
            $rules=[];
            if(!empty($request->cookie_image)){
                $rules['cookie_image']='image|mimes:jpg,jpeg,png|max:2000';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors;

                return redirect()->back()->with(['dismiss' => $errors[0]]);
            }
            $response = $this->settingRepo->saveAdminSetting($request);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', __('Setting updated successfully'));
            } else {
                return redirect()->back()->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            storeException('adminCookieSettingsSave',$e->getMessage());
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }

    public function testMail(Request $request){

        $mailService = new MailService();
        $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $subject = __(' Test Mail | :companyName', ['companyName' => $companyName]);
        $test = $mailService->sendTest('email.test_mail', [], $request->email, "Name", $subject);

        return redirect()->route('adminSettings', ['tab' => 'email'])->with("success", $test['message']);
    }
}
