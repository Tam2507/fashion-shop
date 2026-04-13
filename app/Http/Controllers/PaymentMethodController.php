<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

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
            $validated['logo'] = (new ImageUploadService)->upload($request->file('logo'), 'payment-methods');
        }

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
            $svc = new ImageUploadService;
            $svc->delete($paymentMethod->logo);
            $validated['logo'] = $svc->upload($request->file('logo'), 'payment-methods');
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được cập nhật thành công!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        (new ImageUploadService)->delete($paymentMethod->logo);
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
