<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model {

    use HasFactory;

    protected $table = 'items';

    public $timestamps = true;

    protected $fillable = [
        'quantity',
        'idcart',
        'idproduct',
    ];

    public function getCart(): HasOne {
        return $this->HasOne(Cart::class, 'idcart');
    }

    public function getProduct(): HasOne {
        return $this->hasOne(Product::class, 'idproduct');
    }
}
