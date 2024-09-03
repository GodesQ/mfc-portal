<?php

namespace App\Models;

use App\Notifications\SendEmailVerification;
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
        'contact_number',
        'area',
        'chapter',
        'gender',
        'address',
        'status',
        'dob',
        'role_id',
        'section_id',
        'servant_id',
        'description',
        'mfc_id_number',
        'first_name',
        'last_name',
    ];

    public static array $status = ['Inactive', 'Active'];

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
        'email_verified_at' => 'datetime',
    ];

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

    public function generateNextMfcId()
    {
       $mfc_number = generateRandomSevenNumber();

       $user = User::select('mfc_id_number')->where('mfc_id_number', $mfc_number)->exists();

       while($user) {
            $mfc_number = generateRandomSevenNumber();
       }

       return $mfc_number;
    }

    public function section() : BelongsTo {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function user_details() : HasOne {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function missionary_services() : HasMany {
        return $this->hasMany(UserMissionaryService::class, 'user_id');
    }
}
