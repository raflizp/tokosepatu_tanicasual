<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name','email','password','phone','address','role'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['password'=>'hashed'];
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function orders() { return $this->hasMany(Order::class); }
    public function carts() { return $this->hasMany(Cart::class); }
}