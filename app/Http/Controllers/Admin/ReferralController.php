<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Models\Referral;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    public function index()
    {
        $pageTitle = 'Referral Commissions';
        $referrals = Referral::get();
        $commissionTypes = [
            'deposit_commission'=>'Deposit Commission',
            'plan_subscribe_commission'=>'Plan Subscription',
            'ptc_view_commission'=>'PTC View',
        ];
        return view('admin.referral_setting',compact('pageTitle','referrals','commissionTypes'));
    }




    public function designation(Request $request)
    {
        $pageTitle = 'Referral Designation';
        $designations = Designation::get();
        $designationsFirst = Designation::first();

        $designationArray = [];
        $i = 1;
        $old_value = $designationsFirst->needUser;
        foreach ($designations as $value) {
            $totaluser = 0;
            $totalusers = [];
            $usersCount = User::select(['id','ref_by'])->count();
            $users = User::select(['id','ref_by'])->get();
            $ii = 1;

            foreach ($users as $user) {
                if(countUsers($user,$usersCount,$i)>=$value->needUser){
                    if($i>1){
                        if(countUsers($user,$usersCount,$i-1)>=$old_value){

                            $user->update(['ReferCount'=>countUsers($user,$usersCount,$i),'Referlevel'=>$value->id]);
                        }else{
                        }
                    }else{
                        $user->update(['ReferCount'=>countUsers($user,$usersCount,$i),'Referlevel'=>$value->id]);
                    }





                    // $totaluser += 1;
                    // array_push($totalusers,countUsers($user,$usersCount,$i));
                }
                // echo $ii.'<br>';
                $ii++;
            }
            $old_value = $value->needUser;

            // array_push($designationArray,[
            //     'designation'=>$value->designation,
            //     'needUser'=>$value->needUser,
            //     'totaluser'=> $totaluser,
            //     'totalusers'=> $totalusers,
            // ]);

            // echo $i;

            $i++;
        }

        $autoupdate = $request->autoupdate;
        if(!$autoupdate){
            foreach ($designations as $value) {
                array_push($designationArray,[
                    'id'=>$value->id,
                    'designation'=>$value->designation,
                    'needUser'=>$value->needUser,
                    'totaluser'=> User::where('Referlevel',$value->id)->where('ReferCount', '>=', intval($value->needUser))->where('plan_id','>',0)->count(),
                    // 'totaluser'=> User::where('Referlevel',$value->id)->where('ReferCount', '>=', intval($value->needUser))->count(),
                ]);
            }

            // return $designationArray;

            return view('admin.referral_designation',compact('pageTitle','designations','designationArray'));
        }
    }


    public function designationUser(Request $request,$id)
    {
        $designation = Designation::find($id);
        $pageTitle = $designation->designation.' Users';


        $users = User::where(['Referlevel'=>$designation->id])->where('ReferCount', '>=', intval($designation->needUser))->paginate(getPaginate());

        return view('admin.referral_designationUser',compact('pageTitle','users'));
    }




    public function Updatedesignation(Request $request)
    {

        Designation::truncate();

        $data =  $request->all();

        $designations = $data['designation'];
        $needUsers = $data['needUser'];

        $formCount = count($designations);


        for ($i=0; $i <$formCount ; $i++) {

            $inserData = [
                'designation'=>$designations[$i],
                'needUser'=>$needUsers[$i],
            ];
            Designation::create($inserData);
        }

        $notify[] = ['success', 'Referral Designation updated successfully'];
        return back()->withNotify($notify);



    }


    public function status($type)
    {
        $general = gs();
        if (@$general->$type == 1) {
            @$general->$type = 0;
        }else{
            @$general->$type = 1;
        }
        $general->save();
        $notify[] = ['success', 'Referral commission status updated successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'percent*' => 'required|numeric',
            'commission_type' => 'required|in:deposit_commission,plan_subscribe_commission,ptc_view_commission',
        ]);
        $type = $request->commission_type;

        Referral::where('commission_type',$type)->delete();

        for ($i = 0; $i < count($request->percent); $i++){
            $referral = new Referral();
            $referral->level = $i + 1;
            $referral->percent = $request->percent[$i];
            $referral->commission_type = $request->commission_type;
            $referral->save();
        }

        $notify[] = ['success','Referral commission setting updated successfully'];
        return back()->withNotify($notify);
    }
}
