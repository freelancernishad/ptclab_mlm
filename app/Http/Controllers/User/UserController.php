<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\CommissionLog;
use App\Models\Form;
use App\Models\GatewayCurrency;
use App\Models\Plan;
use App\Models\Ptc;
use App\Models\PtcView;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $ptc       = PtcView::where('user_id', auth()->user()->id)->get(['view_date', 'amount']);

        $chart['click'] = $ptc->groupBy('view_date')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(7)->toArray();

        $chart['amount'] = $ptc->groupBy('vdt')->map(function ($item, $key) {
            return collect($item)->sum('amount');
        })->sort()->reverse()->take(7)->toArray();

        $commissionCount = CommissionLog::where('to_id', auth()->id())->sum('amount');
        $activeAdCount   = Ptc::where('status', 1)->where('user_id', auth()->id())->count();
        $user            = auth()->user();

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'chart', 'user', 'commissionCount', 'activeAdCount'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);

        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);

        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }

        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {

        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }

        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }

        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == 1) {
            return to_route('user.home');
        }

        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];
        $user->profile_complete = 1;
        $user->save();

        $general = gs();

        if ($general->registration_bonus > 0) {
            $user->balance += $general->registration_bonus;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $general->registration_bonus;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Registration Bonus';
            $transaction->remark       = 'registration_bonus';
            $transaction->trx          = getTrx();
            $transaction->save();
        }

        $plan = Plan::where('status', 1)->find($general->default_plan);

        if ($plan) {
            $user->daily_limit = $plan->daily_limit;
            $user->expire_date = now()->addDays($plan->validity);
            $user->plan_id     = $plan->id;
            $user->save();
        }

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function buyPlan(Request $request)
    {
        $request->validate([
            'id'          => 'required',
            'wallet_type' => 'required',
        ]);
        $wallet = $request->wallet_type;

        $plan = Plan::where('status', 1)->findOrFail($request->id);
        $user = auth()->user();


        if ($user->runningPlan && $user->plan_id == $plan->id) {
            $notify[] = ['error', 'You couldn\'t subscribe current package till expired'];
            return back()->withNotify($notify);
        }

        //Direct Checkout
        if ($wallet != 'deposit_wallet') {
            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })->find($request->wallet_type);

            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

            if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
                $notify[] = ['error', 'Please follow deposit limit'];
                return back()->withNotify($notify);
            }

            $data = PaymentController::insertDeposit($gate, $plan);

            session()->put('Track', $data->trx);

            return to_route('user.deposit.confirm');
        } else {
            if ($plan->price > $user->balance) {
                $notify[] = ['error', 'Oops! You\'ve no sufficient balance'];
                return back()->withNotify($notify);
            }
        }

        $user->balance -= $plan->price;
        $user->daily_limit = $plan->daily_limit;
        $user->expire_date = now()->addDays($plan->validity);
        $user->plan_id     = $plan->id;
        $user->save();

        $trx                       = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $plan->price;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Subscribe ' . $plan->name . ' Plan';
        $transaction->trx          = $trx;
        $transaction->remark       = 'subscribe_plan';
        $transaction->save();

        levelCommission($user, $plan->price, 'plan_subscribe_commission', $trx);

        notify($user, 'BUY_PLAN', [
            'plan_name'    => $plan->name,
            'amount'       => showAmount($plan->price),
            'trx'          => $trx,
            'post_balance' => showAmount($user->balance),
        ]);

        $notify[] = ['success', 'You have subscribed to the plan successfully'];
        return back()->withNotify($notify);
    }

    public function commissions(Request $request)
    {
        $pageTitle   = "Commissions";
        $commissions = CommissionLog::where('to_id', auth()->id());

        if ($request->search) {
            $search      = request()->search;
            $commissions = $commissions->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")->orWhereHas('userFrom', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }

        if ($request->remark) {
            $commissions = $commissions->where('type', $request->remark);
        }

        if ($request->level) {
            $commissions = $commissions->where('level', $request->level);
        }

        $commissions = $commissions->with('userFrom')->paginate(getPaginate());
        $levels      = Referral::max('level');
        return view($this->activeTemplate . 'user.commissions', compact('pageTitle', 'commissions', 'levels'));
    }

    public function referredUsers()
    {
        $pageTitle = "Referred Users";
        $refUsers  = User::where('ref_by', auth()->user()->id)->with('plan')->paginate(getPaginate());
        $user      = auth()->user();
        return view($this->activeTemplate . 'user.referred', compact('pageTitle', 'refUsers', 'user'));
    }


    public function referredUsersTree()
    {
         $pageTitle = "Tree View";
        $refUsers  = User::where('ref_by', auth()->user()->id)->with('plan')->paginate(getPaginate());
        $user      = auth()->user();


        return view($this->activeTemplate . 'user.tree',compact('user','pageTitle'));
    }


    public function referredUsersTreeView()
    {
        $user      = auth()->user();
        $tree = getTree($user->id);
        return view('laravelTreeView',compact('tree'));

    }















    public function transfer()
    {
        $pageTitle = 'Transfer Balance';
        $general   = gs();
        if ($general->balance_transfer == 0) {
            $notify[] = ['error', 'User balance transfer currently disabled'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        return view($this->activeTemplate . 'user.transfer_balance', compact('pageTitle'));
    }

    public function transferSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'amount'   => 'required|numeric|gt:0',
        ]);

        $user = auth()->user();
        if ($user->username == $request->username) {
            $notify[] = ['error', 'You cannot send money to your won account'];
            return back()->withNotify($notify);
        }

        $receiver = User::where('username', $request->username)->first();
        if (!$receiver) {
            $notify[] = ['error', 'Receiver not found'];
            return back()->withNotify($notify);
        }

        $general     = gs();
        $charge      = $general->bt_fixed + ($request->amount * $general->bt_percent) / 100;
        $afterCharge = $request->amount + $charge;

        if ($user->balance < $afterCharge) {
            $notify[] = ['error', 'You have no sufficient balance'];
            return back()->withNotify($notify);
        }

        $user->balance -= $afterCharge;
        $user->save();

        $trx = getTrx();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($afterCharge);
        $transaction->charge       = $charge;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->details      = 'Balance transfer to ' . $receiver->username;
        $transaction->remark       = 'balance_transfer';
        $transaction->post_balance = getAmount($user->balance);
        $transaction->save();

        $receiver->balance += $request->amount;
        $receiver->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $receiver->id;
        $transaction->amount       = getAmount($request->amount);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->details      = 'Balance received from ' . $user->username;
        $transaction->remark       = 'balance_received';
        $transaction->post_balance = getAmount($user->balance);
        $transaction->save();

        notify($user, 'BALANCE_TRANSFER', [
            'amount'       => $request->amount,
            'charge'       => $charge,
            'afterCharge'  => $afterCharge,
            'post_balance' => $user->balance,
            'receiver'     => $receiver->username,
            'trx'          => $trx,
        ]);

        notify($receiver, 'BALANCE_RECEIVE', [
            'amount'       => $request->amount,
            'post_balance' => $user->balance,
            'sender'       => $user->username,
            'trx'          => $trx,
        ]);

        $notify[] = ['success', 'Balance transferred successfully'];
        return back()->withNotify($notify);
    }
}
