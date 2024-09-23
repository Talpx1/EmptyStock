<?php

namespace App\Models;

use App\Models\Traits\LogsAllDirtyChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Product extends Model {
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, LogsAllDirtyChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'price',
        'pieces_per_bundle',
        'individually_sellable',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'price' => 'float',
            'pieces_per_bundle' => 'integer',
            'individually_sellable' => 'boolean',
        ];
    }

    /** @return BelongsTo<Company, Product> */
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class);
    }
}
