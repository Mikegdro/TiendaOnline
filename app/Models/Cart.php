<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model {

    use HasFactory;

    protected $table = 'items';

    public $timestamps = true;

    protected $fillable = [
        'quantity',
        'iduser',
    ];

    public function getItems(): HasMany {
        return $this->HasMany(Item::class, 'idcart');
    }

    public function getUser(): BelongsTo {
        return $this->BelongsTo(User::class, 'iduser');
    }

}
