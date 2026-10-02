<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model {
    protected $fillable = ['name','type','account_number','account_name','bank_name','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function getTypeLabelAttribute(): string {
        return match($this->type) {
            'transfer_bank'=>'Transfer Bank',
            'dompet_digital'=>'Dompet Digital',
            'virtual_account'=>'Virtual Account',
            default=>$this->type
        };
    }
}