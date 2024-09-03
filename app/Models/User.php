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
        // Get the latest MFC ID from the database
        $lastUser = User::orderBy('mfc_id_number', 'desc')->first();

        // Extract the numeric part and increment it
        if ($lastUser && $lastUser->mfc_id_number) {
            $lastNumberPart = (int)substr($lastUser->mfc_id_number, 4);
            $nextNumber = $lastNumberPart + 1;
        } else {
            // Start from 1 if no MFC ID exists
            $nextNumber = 1;
        }

        // Pad the number with leading zeros to maintain the length of 7 digits
        $newNumberPart = str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        // Concatenate with the prefix "MFC-"
        return "MFC-" . $newNumberPart;
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
