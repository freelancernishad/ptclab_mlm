<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Searchable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','ver_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function runningPlan(): Attribute
    {
        if ($this->plan && $this->expire_date > now()) {
            $running = true;
        }else{
            $running = false;
        }
        return new Attribute(
            get: fn () => $running,
        );
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function clicks()
    {
        return $this->hasMany(PtcView::class);
    }

    public function commissions()
    {
        return $this->hasMany(CommissionLog::class);
    }


    public function refBy()
    {
        return $this->belongsTo(User::class,'ref_by');
    }

    // SCOPES
    public function scopeActive()
    {
        return $this->where('status', Status::USER_ACTIVE)->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
    }

    public function scopeBanned()
    {
        return $this->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', Status::NO);
    }

    public function scopeMobileUnverified()
    {
        return $this->where('sv', Status::NO);
    }

    public function scopeKycUnverified()
    {
        return $this->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending()
    {
        return $this->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified()
    {
        return $this->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified()
    {
        return $this->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance()
    {
        return $this->where('balance','>', 0);
    }

}
