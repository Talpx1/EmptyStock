<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\LogsAllDirtyChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Profile extends Model {
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory, LogsAllDirtyChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
    ];

    /** @return BelongsTo<User, Profile> */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
