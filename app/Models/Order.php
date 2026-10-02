<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['user_id','order_code','total_amount','shipping_address','notes','city','shipping_cost','phone','payment_method','payment_proof','status','admin_note','paid_at'];
    protected $casts = ['paid_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'menunggu_pembayaran'=>'Menunggu Pembayaran',
            'menunggu_verifikasi'=>'Menunggu Verifikasi',
            'diproses'=>'Diproses','dikirim'=>'Dikirim',
            'selesai'=>'Selesai','dibatalkan'=>'Dibatalkan',
            default=>$this->status
        };
    }
    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'menunggu_pembayaran'=>'bg-yellow-100 text-yellow-800',
            'menunggu_verifikasi'=>'bg-blue-100 text-blue-800',
            'diproses'=>'bg-purple-100 text-purple-800',
            'dikirim'=>'bg-indigo-100 text-indigo-800',
            'selesai'=>'bg-green-100 text-green-800',
            'dibatalkan'=>'bg-red-100 text-red-800',
            default=>'bg-gray-100 text-gray-800'
        };
    }
    public function getFormattedTotalAttribute(): string {
        return 'Rp '.number_format($this->total_amount,0,',','.');
    }
    public function getPaymentMethodLabelAttribute(): string {
        return match($this->payment_method) {
            'transfer_bank'=>'Transfer Bank',
            'dompet_digital'=>'Dompet Digital',
            'virtual_account'=>'Virtual Account',
            default=>$this->payment_method
        };
    }
}