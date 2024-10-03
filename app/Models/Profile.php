<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\LogsAllDirtyChanges;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Profile extends Model {
    public const string ACTIVE_PROFILE_SESSION_KEY = 'auth.user.profile.active';

    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory, LogsAllDirtyChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'shop_id',
        'username',
    ];

    /** @var array<int, string> */
    protected $appends = [
        'has_shop',
    ];

    /** @return BelongsTo<User, Profile> */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Shop, Profile> */
    public function shop(): BelongsTo { //TODO: test
        return $this->belongsTo(Shop::class);
    }

    /** @return Attribute<bool, never> */
    public function hasShop(): Attribute { //TODO: test
        return Attribute::get(fn () => ! is_null($this->shop_id));
    }
}
