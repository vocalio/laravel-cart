<?php

namespace Vocalio\LaravelCart\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasUuids;

    protected $fillable = [
        'items',
    ];

    protected $casts = [
        'items' => AsCollection::class,
    ];

    public function getTable(): string
    {
        return config('cart.table_name');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('cart.user_model'));
    }
}
