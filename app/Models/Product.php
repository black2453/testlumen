<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $table   = 'product';

    public function type()
    {
        return $this->hasOne(Type::class,'id','type_id');
    }

    public function image()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
}
