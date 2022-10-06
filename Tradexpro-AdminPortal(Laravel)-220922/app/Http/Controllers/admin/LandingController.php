<?php

namespace App\Http\Controllers\admin;

use App\Http\Repositories\SettingRepository;
use App\Http\Services\LandingService;
use App\Http\Services\Logger;
use App\Model\AdminSetting;
use App\Model\CustomPage;
use App\Model\LandingFeature;
use App\Model\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    public $settingRepo;
    public $landingService;
    public $logger;
    public function __construct()
    {
        $this->settingRepo = new SettingRepository();
        $this->landingService = new LandingService();
        $this->logger = new Logger();
    }

    // custom page list
    public function adminCustomPageList(Request $request)
    {
        $data['title'] = __("Custom Page List");
        if ($request->ajax()) {
            $cp = CustomPage::select('*')->orderBy('data_order','ASC');
            return datatables($cp)
                ->addColumn('type', function ($item) {
                    return custom_page_type($item->type);
                })
                ->addColumn('actions', function ($item) {
                    $html = '<input type="hidden" value="'.$item->id.'" class="shortable_data">';
                    $html .= '<ul class="d-flex activity-menu">';

                    $html .= ' <li class="viewuser"><a title="Edit" href="' . route('adminCustomPageEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> <span></span></li>';
                    $html .= delete_html('adminCustomPageDelete',encrypt($item->id));
                    $html .=' </ul>';
                    return $html;
                })
                ->rawColumns(['actions'])->make(true);
        }

        return view('admin.custom-page.custom-pages-list', $data);
    }

    // custom page add
    public function adminCustomPageAdd()
    {
        $data['title'] = __("Add Page");
        return view('admin.custom-page.custom-pages', $data);
    }

    // edit the custom page
    public function adminCustomPageEdit($id)
    {
        $data['title'] = __("Update Page");
        $data['cp'] = CustomPage::findOrFail($id);

        return view('admin.custom-page.custom-pages', $data);
    }

    // custom page save setting
    public function adminCustomPageSave(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|max:255',
                'key' => 'required|max:255',
                'type' => 'required|integer',
                'body' => 'required'
            ];
            $messages = [
                'title.required' => __('Title Can\'t be empty!'),
                'type.required' => __('Please select a type'),
                'key.required' => __('Slug Can\'t be empty!'),
                'body.required' => __('Description Can\'t be empty!')
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errors = [];
                $e = $validator->errors()->all();
                foreach ($e as $error) {
                    $errors[] = $error;
                }
                $data['message'] = $errors[0];

                return redirect()->back()->withInput()->with(['dismiss' => $data['message']]);
            }
            $check = $this->landingService->checkKeyCustom($request->key,$request->edit_id);
            if(!$check){
                return redirect()->back()->withInput()->with(['dismiss' => 'Duplicate Key found!']);
            }
            $custom_page = [
                'title' => $request->title
                , 'key' => $request->key
                , 'type' => $request->type
                , 'description' => $request->body
                , 'status' => STATUS_SUCCESS
            ];
            CustomPage::updateOrCreate(['id' => $request->edit_id], $custom_page);

            if ($request->edit_id) {
                $message = __('Custom page updated successfully');
            } else {
                $message = __('Custom Page created successfully');
            }

            return redirect()->route('adminCustomPageList')->with(['success' => $message]);
        } catch (\Exception $e) {
            $this->logger->log('adminCustomPageSave', $e->getMessage());
            return redirect()->back()->with(['dismiss' => __('Something went wrong')]);
        }
    }

    // delete custom page
    public function adminCustomPageDelete($id)
    {
        if (isset($id)) {
            CustomPage::where(['id' => decrypt($id)])->delete();
        }

        return redirect()->back()->with(['success' => __('Deleted Successfully')]);
    }


    // change custom page order
    public function customPageOrder(Request $request)
    {
        $vals = explode(',',$request->vals);
        foreach ($vals as $key => $item){
            CustomPage::where('id',$item)->update(['data_order'=>$key]);
        }

        return response()->json(['message'=>__('Page ordered change successfully')]);
    }

//    Landing Settings
    public function adminLandingSetting(Request $request)
    {
        if (isset($_GET['tab'])) {
            $data['tab']=$_GET['tab'];
        } else {
            $data['tab']='hero';
        }
        $data['adm_setting'] = allsetting();

        return view('admin.settings.landing-settings',$data);
    }

    // save cms setting
    public function adminLandingSettingSave(Request $request)
    {
        $rules = [];
        foreach ($request->all() as $key => $item) {
            if ($request->hasFile($key)) {
                $rules[$key] = 'image|mimes:jpg,jpeg,png|max:2000';
            }
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

        foreach ($request->all() as $key => $item) {
            if (!empty($request->$key)) {
                $setting = AdminSetting::where('slug', $key)->first();
                if (empty($setting)) {
                    $setting = new AdminSetting();
                    $setting->slug = $key;
                }
                if ($request->hasFile($key)) {
                    $setting->value = uploadFile($request->$key, IMG_PATH, isset(allsetting()[$key]) ? allsetting()[$key] : '');
                } else {
                    $setting->value = $request->$key;
                }
                $setting->save();
            }
        }

        return redirect()->back()->with(['success' => __('Landing Page Setting Successfully Updated!')]);

    }

    //  List
    public function adminFeatureList(Request $request)
    {
        $data['title'] = __('Landing Feature');
        if ($request->ajax()) {
            $data['items'] = LandingFeature::orderBy('id', 'desc');
            return datatables()->of($data['items'])
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at;
                })
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminFeatureEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> </li>
                        <li class="deleteuser"><a href="' . route('adminFeatureDelete', $item->id) . '"><i class="fa fa-trash"></i></a></li>
                        </ul>';
                })
                ->rawColumns(['actions','status'])
                ->make(true);
        }

        return view('admin.landing_feature.list', $data);
    }

    // View Add new
    public function adminFeatureAdd()
    {
        $data['title']=__('Add Landing Feature');
        $data['button_title']=__('Add Feature');
        return view('admin.landing_feature.addEdit',$data);
    }

    // Create New
    public function adminFeatureSave(Request $request)
    {
        try {
            $rules = [
                'feature_title'=>'required',
                'description'=>'required',
                'status'=>'required',
            ];
            if(!empty($request->feature_icon)){
                $rules['feature_icon']='image|mimes:jpg,jpeg,png,PNG,JPG,JPEG|max:2000';
            }
            $messages = [
                'feature_title.required' => __('Title field can not be empty'),
                'description.required' => __('Details field can not be empty'),
                'status.required' => __('Status field can not be empty'),
                'feature_icon.image' => __('Feature icon should be an image'),
                'feature_icon.mimes' => __('Feature icon should be jpg or png type image'),
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

            $response = $this->landingService->saveLandingFeature($request);
            if ($response['success'] == true) {
                return redirect()->route('adminFeatureList')->with(['success'=> $response['message']]);
            } else {
                return redirect()->back()->with(['dismiss'=> $response['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // Edit
    public function adminFeatureEdit($id)
    {
        $data['title'] = __('Update Landing Feature');
        $data['button_title'] = __('Update Feature');
        $data['item'] = LandingFeature::findOrFail($id);
        if (isset($data['item'])) {
            return view('admin.landing_feature.addEdit',$data);
        } else {
            return redirect()->back()->with('dismiss', __('Data not found'));
        }
    }

    // Delete
    public function adminFeatureDelete($id)
    {
        if(isset($id)) {
            LandingFeature::where(['id'=>$id])->delete();
        }
        return redirect()->back()->with(['success'=>__('Deleted Successfully!')]);
    }
    //  List
    public function adminSocialMediaList(Request $request)
    {
        $data['title'] = __('Social Media');
        if ($request->ajax()) {
            $data['items'] = SocialMedia::orderBy('id', 'desc');
            return datatables()->of($data['items'])
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at;
                })
                ->addColumn('actions', function ($item) {
                    return '<ul class="d-flex activity-menu">
                        <li class="viewuser"><a href="' . route('adminSocialMediaEdit', $item->id) . '"><i class="fa fa-pencil"></i></a> </li>
                        <li class="deleteuser"><a href="' . route('adminSocialMediaDelete', $item->id) . '"><i class="fa fa-trash"></i></a></li>
                        </ul>';
                })
                ->rawColumns(['actions','status'])
                ->make(true);
        }

        return view('admin.social_media.list', $data);
    }

    // View Add new
    public function adminSocialMediaAdd()
    {
        $data['title']=__('Add Social Media');
        $data['button_title']=__('Add Media');
        return view('admin.social_media.addEdit',$data);
    }

    // Create New
    public function adminSocialMediaSave(Request $request)
    {
        try {
            $rules = [
                'media_title'=>'required',
                'media_link'=>'required',
                'status'=>'required',
            ];
            if(!empty($request->feature_icon)){
                $rules['feature_icon']='image|mimes:jpg,jpeg,png,PNG,JPG,JPEG|max:2000';
            }
            $messages = [
                'media_title.required' => __('Title field can not be empty'),
                'media_link.required' => __('Link field can not be empty'),
                'status.required' => __('Status field can not be empty'),
                'media_icon.image' => __('Media icon should be an image'),
                'media_icon.mimes' => __('Media icon should be jpg or png type image'),
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

            $response = $this->landingService->saveLandingSocialMedia($request);
            if ($response['success'] == true) {
                return redirect()->route('adminSocialMediaList')->with(['success'=> $response['message']]);
            } else {
                return redirect()->back()->with(['dismiss'=> $response['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', __('Something went wrong'));
        }
    }

    // Edit
    public function adminSocialMediaEdit($id)
    {
        $data['title'] = __('Update Social Media');
        $data['button_title'] = __('Update Media');
        $data['item'] = SocialMedia::findOrFail($id);
        if (isset($data['item'])) {
            return view('admin.social_media.addEdit',$data);
        } else {
            return redirect()->back()->with('dismiss', __('Data not found'));
        }
    }

    // Delete
    public function adminSocialMediaDelete($id)
    {
        if(isset($id)) {
            SocialMedia::where(['id'=>$id])->delete();
        }
        return redirect()->back()->with(['success'=>__('Deleted Successfully!')]);
    }

    public function customPageSlugCheck(Request $request){
        return $this->landingService->customPageSlugCheck($request->except('_token'));
    }
}
