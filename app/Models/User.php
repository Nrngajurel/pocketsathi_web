<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];



    /**
     * Scope a query to find nearby users within a specified radius.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $lat
     * @param float $lon
     * @param float $radius
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearby($query, $lat, $lon, $radius)
    {
        return $query
            ->selectRaw('*, (6371 * acos(cos(radians(?)) * cos(radians(users.lat)) * cos(radians(users.long) - radians(?)) + sin(radians(?)) * sin(radians(users.lat)))) AS distance', [$lat, $lon, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }
}