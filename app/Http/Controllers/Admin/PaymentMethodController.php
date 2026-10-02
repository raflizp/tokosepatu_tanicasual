<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public static function middleware(): array
    {
        return ['auth', 'admin'];
    }

    public function index()
    {
        $methods = PaymentMethod::orderBy('type')->orderBy('name')->get();
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'type'           => 'required|in:transfer_bank,dompet_digital,virtual_account',
            'account_number' => 'required|string|max:100',
            'account_name'   => 'required|string|max:100',
            'bank_name'      => 'nullable|string|max:100',
        ]);
        PaymentMethod::create($request->all());
        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment-methods.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $request->validate([
            'name'           => 'required|string|max:100',
            'type'           => 'required|in:transfer_bank,dompet_digital,virtual_account',
            'account_number' => 'required|string|max:100',
            'account_name'   => 'required|string|max:100',
            'bank_name'      => 'nullable|string|max:100',
        ]);
        $method->update($request->all());
        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}