@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Test Variant Management UI</h2>
    
    @php
        $product = \App\Models\Product::with('variants')->first();
    @endphp
    
    @if($product)
        @include('admin.products.partials.variant-management')
    @else
        <p>Không có sản phẩm nào</p>
    @endif
</div>
@endsection
