<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\LogsAllDirtyChanges;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $profiles
 * @property-read Profile $active_profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Profile> $inactive_profiles
 */
final class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, LogsAllDirtyChanges, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
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
    ];

    /** @var array<int, string> */
    protected $appends = [
        'active_profile',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** @return HasMany<Profile> */
    public function profiles(): HasMany {
        return $this->hasMany(Profile::class);
    }

    /** @return Attribute<Profile, never> */
    public function activeProfile(): Attribute {
        return Attribute::get(fn () => Session::get(Profile::ACTIVE_PROFILE_SESSION_KEY));
    }

    /** @return Attribute<\Illuminate\Database\Eloquent\Collection<int, Profile>, never> */
    public function inactiveProfiles(): Attribute {
        return Attribute::get(fn () => $this->profiles()->whereNot('id', $this->active_profile->id)->get());
    }
}
