<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['category_id','name','slug','description','price','stock','image','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function category() { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function primaryImage() { return $this->hasOne(ProductImage::class)->where('is_primary', true); }
    public function getFormattedPriceAttribute(): string {
        return 'Rp '.number_format($this->price,0,',','.');
    }
}