<?php

namespace App\Http\Services;


use App\Http\Repositories\AdminSettingRepository;
use App\Model\AdminSetting;
use App\Model\Announcement;
use App\Model\CoinPair;
use App\Model\LandingBanner;
use Illuminate\Support\Facades\DB;


class AdminSettingService extends CommonService
{
    public $model = AdminSetting::class;
    public $repository = AdminSettingRepository::class;
    public $logger;
    public function __construct()
    {
        parent::__construct($this->model,$this->repository);
        $this->logger = new Logger();
    }

    public function generalSetting($data)
    {
        $admin_setting_repo = new AdminSettingRepository();
        try {
            foreach ($data as $key => $val) {
                $admin_setting_repo->updateOrCreate($key, $val);
            }

            return ['success' => true, 'data' => $data, 'message' => 'updated.successfully'];
        } catch (\Exception $e) {

            return ['success' => false, 'data' => [], 'message' => 'something.went.wrong'];
        }
    }

    public function apiCredentialsUpdate($data)
    {
        $admin_setting_repo = new AdminSettingRepository();

        try {

            if (isset($data['coin_id'][0])) {

                for ($i = 0; $i < count($data['coin_id']); $i++) {

                    if (!empty($data['coin_id'][$i])) {
                        $coin_id = decryptId($data['coin_id'][$i]);

                        if (is_numeric($coin_id) && is_numeric($data['withdrawal_fee_method'][$i]) && is_numeric($data['withdrawal_fee_percent'][$i]) && is_numeric($data['withdrawal_fee_fixed'][$i])) {
                            $admin_setting_repo->ApiCredentialsUpdateOrCreate($coin_id, $data['api_service'][$i], $data['withdrawal_fee_method'][$i], $data['withdrawal_fee_percent'][$i], $data['withdrawal_fee_fixed'][$i]);
                        }
                    }
                }
                return ['success' => true, 'data' => $data, 'message' => 'updated.successfully'];
            } else {
                return ['success' => false, 'data' => [], 'message' => 'coin.not.valid'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'data' => [], 'message' => __('Something Went wrong.')];
        }
    }

    public function tradeSetting($data)
    {

        try {

            foreach ($data as $key => $val) {
                $labelName = str_replace('_', ' ', $key);
                $value = [
                    'value' => $val,
//                    'label' => $labelName,
//                    'type' => 'text'
                ];

                $this->updateOrCreateTrade($key, $value);
            }

            return ['success' => true, 'data' => $data, 'message' => __('Updated Successfully.')];
        } catch (\Exception $e) {

            return ['success' => false, 'data' => [], 'message' => __('Something Went wrong.' . $e->getMessage())];
        }
    }

    public function savePairSetting($request)
    {
        $setting_repo = new AdminSettingRepository();
        try {
            if ($request->parent_coin_id == $request->child_coin_id) {
                return ['success' => false, 'message' => __('Same coin pair is not possible')];
            }
            $coinPair = CoinPair::where(['parent_coin_id' => $request->parent_coin_id, 'child_coin_id'=> $request->child_coin_id])->first();

            if (isset($request->edit_id)) {
                if (isset($coinPair) && ($coinPair->id != decrypt($request->edit_id))) {
                    return ['success' => false, 'message' => __('This coin pair already exist')];
                }

                $setting_repo->updateOrCreateCoinPair($request, $request->edit_id);
                $message = __('Updated Successfully.');
            } else {
                if (isset($coinPair)) {
                    return ['success' => false, 'message' => __('This coin pair already exist')];
                }

                $setting_repo->updateOrCreateCoinPair($request);
                $message = __('Added Successfully.');
            }

            return ['success' => true, 'message' => $message];
        } catch (\Exception $e) {
            storeException('savePairSetting', $e->getMessage());
            return ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        }
    }

    public function changeCoinPairStatus($request)
    {
        $setting_repo = new AdminSettingRepository();
        try {
            $pair = CoinPair::find(decrypt($request->active_id));
            $success = false;
            $message = __('Pair not found');
            if (isset($pair)) {
                if ($pair->status == STATUS_ACTIVE) {
                    $pair->update(['status' => STATUS_DEACTIVE]);
                } else {
                    $pair->update(['status' => STATUS_ACTIVE]);
                }
                $success = true;
                $message = __('Status updated successfully');
            }

            return ['success' => $success, 'message' => $message];
        } catch (\Exception $e) {

            return ['success' => false, 'message' => __('Something went wrong')];
        }
    }

    public function updateOrCreateTrade($slug, $value)
    {
        return AdminSetting::updateOrCreate(['slug' => $slug], $value);
    }

    public function saveAnnouncement($request)
    {
        $response = ['success' => false, 'message' => __('Something went wrong')];
        try {
            $data = [
                'title'=> $request->title,
                'description'=> $request->details,
                'status'=> $request->status
            ];
            $slug = make_unique_slug($request->title,'announcements');
            if (empty($request->edit_id)) {
                $data['slug'] = $slug;
            }
            $old_img = '';
            if (!empty($request->edit_id)) {
                $item = Announcement::where(['id'=>$request->edit_id])->first();
                if(isset($item) && (!empty($item->image))) {
                    $old_img = $item->image;
                }
            }
            if (!empty($request->image)) {
                $icon = uploadFile($request->image,IMG_PATH,$old_img);
                if ($icon != false) {
                    $data['image'] = $icon;
                }
            }
            if(!empty($request->edit_id)) {
                Announcement::where(['id'=>$request->edit_id])->update($data);
                $response = ['success' => true, 'message' => __('Announcement updated successfully!')];
            } else {
                Announcement::create($data);
                $response = ['success' => true, 'message' => __('Announcement created successfully!')];
            }
        } catch (\Exception $e) {
            $this->logger->log('saveAnnouncement', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong')];
        }
        return $response;
    }

    public function saveBanner($request)
    {
        $response = ['success' => false, 'message' => __('Something went wrong')];
        try {
            $data = [
                'title'=> $request->title,
                'description'=> $request->body,
                'status'=> $request->status
            ];
            $slug = make_unique_slug($request->title,'landing_banners');
            if (empty($request->edit_id)) {
                $data['slug'] = $slug;
            }
            $old_img = '';
            if (!empty($request->edit_id)) {
                $item = LandingBanner::where(['id'=>$request->edit_id])->first();
                if(isset($item) && (!empty($item->image))) {
                    $old_img = $item->image;
                }
            }
            if (!empty($request->image)) {
                $icon = uploadFile($request->image,IMG_PATH,$old_img);
                if ($icon != false) {
                    $data['image'] = $icon;
                }
            }
            if(!empty($request->edit_id)) {
                LandingBanner::where(['id'=>$request->edit_id])->update($data);
                $response = ['success' => true, 'message' => __('Banner updated successfully!')];
            } else {
                LandingBanner::create($data);
                $response = ['success' => true, 'message' => __('Banner created successfully!')];
            }
        } catch (\Exception $e) {
            $this->logger->log('saveBanner', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong')];
        }
        return $response;
    }

    // delete coin pair
    public function coinPairsDeleteProcess($id)
    {
        try {
            $coinPair = CoinPair::find($id);
            if ($coinPair) {
                $check = checkCoinPairDeleteCondition($coinPair);
                if ($check['success'] == false) {
                    return ['success' => false, 'message' => $check['message']];
                }
                DB::table('coin_pairs')->where(['id' => $id])->delete();
                $response = ['success' => true, 'message' => __('Pair deleted successfully')];
            } else {
                $response = ['success' => false, 'message' => __('Pair not found')];
            }
        } catch (\Exception $e) {
            storeException('coinPairsDeleteProcess', $e->getMessage());
            $response = ['success' => false, 'message' => __('Something went wrong')];
        }
        return $response;
    }
}
