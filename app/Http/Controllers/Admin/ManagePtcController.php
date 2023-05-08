<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ptc;
use App\Models\PtcView;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagePtcController extends Controller
{
    public function index()
    {
        $pageTitle = 'PTC Advertisements';
        $ads = $this->ptcData();
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    public function pending()
    {
        $pageTitle = 'Pending PTC Advertisements';
        $ads = $this->ptcData('pending');
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    public function active()
    {
        $pageTitle = 'Active PTC Advertisements';
        $ads = $this->ptcData('active');
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    public function inactive()
    {
        $pageTitle = 'Inactive PTC Advertisements';
        $ads = $this->ptcData('inactive');
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected PTC Advertisements';
        $ads = $this->ptcData('rejected');
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    private function ptcData($scope = null)
    {
        if ($scope) {
            $ads = Ptc::$scope()->with('user')->orderBy('id', 'desc');
        } else {
            $ads = Ptc::with('user')->orderBy('id', 'desc');
        }

        return $ads->paginate(getPaginate());
    }

    public function create(Request $request)
    {
        $pageTitle = 'Add Advertisement';
        $ads_settings = gs()->ads_setting->adsData;
        return view('admin.ptc.create', compact('pageTitle','ads_settings'));
    }




    public function adsViewed($id)
    {
        // $this->userPostEnabled();
        $pageTitle = 'My Ads Viewed';

        $ads       = PtcView::with(['user','ptc'])->where(['ptc_id'=>$id])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.ptc.adsViewed', compact('ads', 'pageTitle'));
    }

    public function adsViewedStatus($id)
    {
        // $this->userPostEnabled();
        $pageTitle = 'My Ads Viewed';

        $ptc       = PtcView::with(['user','ptc'])->find($id);

        return view('admin.ptc.adsViewedStatus', compact('ptc', 'pageTitle'));
    }

    public function confirmStatus(Request $request,$id)
    {
        $status = $request->status;


         $ptc_view = PtcView::with(['ptc','user'])->find($id);





        if($status=='Approve'){

            if($ptc_view->ptc->IfrOr=='Task ads'){

                $ptc_view->user->balance += $ptc_view->ptc->amount;
                $ptc_view->user->save();

                if($ptc_view->status=='Rejected'){

                    $ptc_view->ptc->showed +=1;
                    $ptc_view->ptc->remain -=1;
                    $ptc_view->ptc->save();
                }



            }

            if($ptc_view->ptc->IfrOr=='Task ads'){
   
                $trx                       = getTrx();
                levelCommission($ptc_view->user, $ptc_view->ptc->amount, 'ptc_view_commission', $trx);
            }

            $ptc_view->update(['status'=>'Approved']);
            $notify[] = ['success', 'Successfully Approved this ads'];
            return redirect()->route('admin.ptc.viewed',$ptc_view->ptc->id)->withNotify($notify);

        }else{


            $rejectReason = $request->rejectReason;
            if($rejectReason){
                $ptc_view->update(['rejectReason'=>$rejectReason,'status'=>'Rejected']);
            }else{

                $ptc_view->update(['status'=>'Rejected']);
            }
            $ptc_view->ptc->showed -=1;
            $ptc_view->ptc->remain +=1;
            $ptc_view->ptc->save();


            $notify[] = ['success', 'Successfully Rejected this ads'];
            return redirect()->route('admin.ptc.viewed',$ptc_view->ptc->id)->withNotify($notify);
        }
    }





    public function edit(Request $request, $id)
    {
        $pageTitle = 'Edit Advertisement';
        $ptc = Ptc::findOrFail($id);
        return view('admin.ptc.edit', compact('pageTitle', 'ptc'));
    }

    public function store(Request $request)
    {
        $IfrOr =  $request->IfrOr;
        if($IfrOr=='iframe'){
            $this->validation($request, [
                'website_link' => 'nullable|url|required_without_all:banner_image,script,youtube',
                'banner_image' => 'nullable|mimes:jpeg,jpg,png,gif|required_without_all:website_link,script,youtube',
                'script' => 'nullable|required_without_all:website_link,banner_image,youtube',
                'youtube' => 'nullable|url|required_without_all:website_link,banner_image,script',
            ]);
        }

        $ptc = new Ptc();
        $this->submit($request, $ptc);
        $notify[] = ['success', 'Advertisement added successfully.'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request);
        $ptc = Ptc::findOrFail($id);
        $this->submit($request, $ptc, 1);
        $notify[] = ['success', 'Advertisement updated successfully.'];
        return back()->withNotify($notify);
    }


    public function submit($request, $ptc, $isUpdate = 0)
    {
        $ptc->title = $request->title;
        $ptc->amount = $request->amount;
        $ptc->duration = $request->duration;
        $ptc->max_show = $request->max_show;
        if (!$isUpdate) {
            $ptc->remain = $request->max_show;
        }
        $ptc->ads_type = $request->ads_type;
        $user = $ptc->user;
        if ($isUpdate && $request->status == 3 && $ptc->user_id != 0 && $ptc->status != 3) {
            $general = gs();
            if ($ptc->ads_type == 1) {
                $price = @$general->ads_setting->ad_price->url ?? 0;
            } elseif ($ptc->ads_type == 2) {
                $price = @$general->ads_setting->ad_price->image ?? 0;
            } elseif ($ptc->ads_type == 3) {
                $price = @$general->ads_setting->ad_price->script ?? 0;
            } else {
                $price = @$general->ads_setting->ad_price->youtube ?? 0;
            }
            $amount = $ptc->remain * $price;
            $user->balance += $amount;
            $user->save();

            $trx = getTrx();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'PTC advertisement rejected';
            $transaction->remark = 'ad_reject';
            $transaction->trx = $trx;
            $transaction->save();

            notify($user, 'PTC_REJECT', [
                'title' => $ptc->title,
                'quantity' => $ptc->max_show,
                'duration' => $ptc->duration,
                'back_amount' => $amount,
                'post_balance' => $user->balance,
                'trx' => $trx,
            ]);
        }

        if ($ptc->status == 2 && $request->status == 1 && $user) {
            notify($user, 'PTC_APPROVE', [
                'title' => $ptc->title,
                'quantity' => $ptc->max_show,
                'duration' => $ptc->duration,
            ]);
        }

        if ($isUpdate) {
            $ptc->status = $request->status ?? 1;
        } else {
            $ptc->status = ($request->status == 'on' ? 1 : 0);
        }
        $ptc->filesSupports = json_encode($request->filesSupports);
        $IfrOr =  $request->IfrOr;
        $ptc->IfrOr = $IfrOr;
        if($IfrOr=='iframe'){


        if ($request->ads_type == 1) {
            $ptc->ads_body = $request->website_link;
        } elseif ($request->ads_type == 2) {

            if ($request->hasFile('banner_image')) {
                if ($isUpdate == 1) {
                    $old = $ptc->ads_body;
                    fileManager()->removeFile(getFilePath('ptc') . '/' . $old);
                }
                $directory = date("Y") . "/" . date("m") . "/" . date("d");
                $path = getFilePath('ptc') . '/' . $directory;
                $filename = $directory . '/' . fileUploader($request->banner_image, $path);
                $ptc->ads_body = $filename;
            }
        } elseif ($request->ads_type == 3) {
            $ptc->ads_body = $request->script;
        } else {
            $ptc->ads_body = $request->youtube;
        }

    }else{

        $ptc->ads_body = $request->ads_body;
    }


        $ptc->save();
    }


    public function validation($request, $rules = [])
    {
        $globalRules = [
            'title' => 'required',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:1',
            'max_show' => 'required|numeric|min:1',
            'ads_type' => 'required|integer',
        ];
        $rules = array_merge($globalRules, $rules);
        $request->validate($rules);
    }
}
