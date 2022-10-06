<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Admin\CurrencyRequest;
use App\Http\Services\CurrencyService;
use App\Model\CurrencyList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    private $service;
    function __construct()
    {
        $this->service = new CurrencyService();
    }
    /*
   *
   * adminCurrencyList
   * Show the list of specified resource.
   * @return \Illuminate\Http\Response
   *
   */
    public function adminCurrencyList()
    {
        $data['title'] = __('Fiat Currency List');
        $data['items'] = CurrencyList::get();

        return view('admin.currency.list', $data);
    }
    /*
     * New Currency Add
     *
     * Show the Empty form.
     * @return \Illuminate\Http\Response
     */
    public function adminCurrencyAdd(){
        $data['title'] = __('Fiat Currency Add');
        return view('admin.currency.addEdit',$data);
    }
    /*
     * Specific Currency Edit Form
     *
     * Show the Specific Currency form.
     * @return \Illuminate\Http\Response
     */
    public function adminCurrencyEdit($id){
        $data['title'] = __('Fiat Currency Edit');
        $data['item'] = CurrencyList::find($id);
        return view('admin.currency.addEdit',$data);
    }
    /*
     * Currency Add Edit
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCurrencyAddEdit(CurrencyRequest $request){
        $response = $this->service->currencyAddEdit($request);
        if($response["success"]) return redirect()->route("adminCurrencyList")->with("success",$response["message"]);
        return redirect()->route("adminCurrencyList")->with("dismiss",$response["message"]);
    }
    /*
     * Currency item status update
     *
     * @return \Illuminate\Http\Response json
     */
    public function adminCurrencyStatus(Request $request){
        $response = $this->service->currencyStatusUpdate($request->active_id);
        return response()->json(["success" => $response]);
    }
    /*
     * Currency rate update Api
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCurrencyRate(){
        $this->service->currencyRateSave();
        $response = $this->service->response;
        if($response["success"]) return redirect()->route("adminCurrencyList")->with("success",$response["message"]);
        return redirect()->route("adminCurrencyList")->with("dismiss",$response["message"]);
    }
    /*
     * Get All Currency
     *
     * @return \Illuminate\Http\Response
     */
    public function adminAllCurrency(Request $request){
        $this->service->saveAllCurrency();
        return response()->json(["status" => true]);
    }
}
