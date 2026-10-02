<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create(['name'=>'Administrator','email'=>'admin@tokosepatu.com','password'=>Hash::make('admin123'),'role'=>'admin','phone'=>'08100000000','address'=>'Jakarta']);
        User::create(['name'=>'Budi Santoso','email'=>'budi@example.com','password'=>Hash::make('password'),'role'=>'user','phone'=>'08111111111','address'=>'Jl. Contoh No. 1, Jakarta']);

        $cats = [
            ['name'=>'Sepatu','slug'=>'sepatu','description'=>'Koleksi sepatu'],
            ['name'=>'Jaket','slug'=>'jaket','description'=>'Koleksi jaket'],
            ['name'=>'Celana','slug'=>'celana','description'=>'Koleksi celana'],
            ['name'=>'Topi','slug'=>'topi','description'=>'Koleksi topi'],
        ];
        foreach ($cats as $c) Category::create($c);

        $products = [
            ['category_id'=>1,'name'=>'Sepatu Sneakers Pria Putih','price'=>250000,'stock'=>20,'description'=>'Sneakers putih minimalis.'],
            ['category_id'=>1,'name'=>'Sepatu Casual Wanita Hitam','price'=>195000,'stock'=>15,'description'=>'Sepatu casual elegan.'],
            ['category_id'=>1,'name'=>'Sepatu Lari Sport','price'=>320000,'stock'=>10,'description'=>'Sepatu lari nyaman.'],
            ['category_id'=>2,'name'=>'Jaket Bomber Hitam','price'=>280000,'stock'=>12,'description'=>'Jaket bomber stylish.'],
            ['category_id'=>2,'name'=>'Jaket Hoodie Abu-abu','price'=>210000,'stock'=>18,'description'=>'Hoodie berbahan fleece.'],
        ];
        foreach ($products as $p) Product::create(array_merge($p, ['slug'=>Str::slug($p['name']).'-'.Str::random(4)]));

        $methods = [
            ['name'=>'BCA','type'=>'transfer_bank','account_number'=>'1234567890','account_name'=>'Toko Sepatu Indo','bank_name'=>'Bank BCA'],
            ['name'=>'Mandiri','type'=>'transfer_bank','account_number'=>'0987654321','account_name'=>'Toko Sepatu Indo','bank_name'=>'Bank Mandiri'],
            ['name'=>'GoPay','type'=>'dompet_digital','account_number'=>'08111111111','account_name'=>'Toko Sepatu Indo','bank_name'=>null],
            ['name'=>'OVO','type'=>'dompet_digital','account_number'=>'08122222222','account_name'=>'Toko Sepatu Indo','bank_name'=>null],
            ['name'=>'VA BNI','type'=>'virtual_account','account_number'=>'8880012345678','account_name'=>'Toko Sepatu Indo','bank_name'=>'Bank BNI'],
        ];
        foreach ($methods as $m) PaymentMethod::create($m);
    }
}