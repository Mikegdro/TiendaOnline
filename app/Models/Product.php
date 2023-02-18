<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model {

    use HasFactory;

    protected $table = 'products';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'categoryid',
        'thumbnail',
        'stock',
        'brand',
        'rating',
        'discount'
    ];

    public function getCategory(): BelongsTo {
        return $this->BelongsTo(Category::class, 'categoryid');
    }


}
