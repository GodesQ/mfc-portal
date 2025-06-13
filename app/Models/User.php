<?php

namespace App\Models;

use App\Notifications\SendEmailVerification;
use App\Services\ExceptionHandlerService;
use App\Services\SmsService;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'username',
        'email_verified_at',
        'contact_number_verified_at',
        'country_code',
        'contact_number',
        'area',
        'chapter',
        'gender',
        'status',
        'dob',
        'role_id',
        'section_id',
        'servant_id',
        'description',
        'mfc_id_number',
        'first_name',
        'last_name',
        'created_at',
    ];

    public static array $status = ['inactive', 'active'];

    public static array $chapter = ['Chapter 1', 'Chapter 2', 'Chapter 3'];

    public static array $gender = ['Brother', 'Sister', 'Others'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'country_code' => 'integer',
        'email_verified_at' => 'datetime',
        'contact_number_verified_at' => 'datetime',
    ];

    public function tithes()
    {
        return $this->hasMany(Tithe::class, 'mfc_user_id', 'mfc_id_number');
    }

    public function sendEmailVerificationNotification()
    {
        $otp = random_int(1000, 9999);

        OTP::create([
            'otp_code' => $otp,
            'user_id' => $this->id,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $date = Carbon::now()->format('F j, Y');

        $this->notify(new SendEmailVerification($otp, $this, $date));

        return redirect()->route('verification.notice');
    }

    public function sendOTPVerificationNotification()
    {
        try {
            $otp = 123456;

            if (config('services.sms.enable')) {
                $otp = random_int(100000, 999999);
            }

            $otp = OTP::create([
                'otp_code' => $otp,
                'user_id' => $this->id,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            if (config('services.sms.enable')) {
                $smsService = new SmsService;
                $request_model = $smsService->request_model($this, $otp);
                $response = $smsService->send($request_model);

                return $response;
            }

        } catch (Exception $exception) {
            throw $exception;
        }

    }

    public function generateNextMfcId()
    {
        $mfc_number = generateNewMFCId();

        $user = User::select('mfc_id_number')->where('mfc_id_number', $mfc_number)->exists();

        while ($user) {
            $mfc_number = generateNewMFCId();
        }

        return $mfc_number;
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function user_details(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function missionary_services(): HasMany
    {
        return $this->hasMany(UserMissionaryService::class, 'user_id');
    }
}
