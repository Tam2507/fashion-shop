<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->paginate(10);
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'position' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment-methods', 'public');
        }

        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active') ? true : false;

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được tạo thành công!');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'position' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
                Storage::disk('public')->delete($paymentMethod->logo);
            }
            $validated['logo'] = $request->file('logo')->store('payment-methods', 'public');
        }

        // Handle is_active checkbox
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được cập nhật thành công!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->logo && Storage::disk('public')->exists($paymentMethod->logo)) {
            Storage::disk('public')->delete($paymentMethod->logo);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được xóa thành công!');
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        
        $status = $paymentMethod->is_active ? 'kích hoạt' : 'tạm dừng';
        return response()->json([
            'success' => true,
            'message' => "Phương thức thanh toán đã được {$status}",
            'is_active' => $paymentMethod->is_active
        ]);
    }
}
