<?php

namespace App\Models;

use App\Models\Traits\LogsAllDirtyChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Shop extends Model {
    /** @use HasFactory<\Database\Factories\ShopFactory> */
    use HasFactory, LogsAllDirtyChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slogan',
        'description',
        'vat_number',
        'iban',
    ];

    /** @return HasMany<Product> */
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
