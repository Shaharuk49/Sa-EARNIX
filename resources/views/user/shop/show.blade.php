@extends('layouts.user')
@section('title', $product->name . ' - SA EarniX')

@section('content')
<div class="container py-4">

    <a href="{{ route('user.shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill mb-4">
        <i class="fas fa-arrow-left me-1"></i>Back to Shop
    </a>

    <div class="row g-4">
        {{-- Product Images --}}
        <div class="col-md-5">
            @php $images = $product->media->where('type','image'); @endphp
            @if($images->isNotEmpty())
            <img src="{{ asset('storage/' . $images->first()->file_path) }}"
                 class="img-fluid rounded-4 shadow-sm w-100" style="max-height:350px;object-fit:cover" alt="{{ $product->name }}">
            @else
            <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center"
                 style="height:280px;background:var(--bs-light)">
                <i class="fas fa-box-open fa-3x text-muted"></i>
            </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="col-md-7">
            <p class="text-muted small mb-1">{{ $product->category->name ?? '' }}</p>
            <h4 class="fw-bold mb-2">{{ $product->name }}</h4>

            <div class="d-flex gap-3 mb-3">
                <div>
                    <small class="text-muted d-block">Admin Price</small>
                    <span class="fw-bold text-dark">৳{{ number_format($product->final_price, 2) }}</span>
                </div>
                <div class="vr"></div>
                <div>
                    <small class="text-muted d-block">Your Selling Price</small>
                    <span class="text-muted small">You set this — earn the difference</span>
                </div>
            </div>

            @if($product->description)
            <p class="text-muted small mb-3">{{ $product->description }}</p>
            @endif

            @if($product->stock <= 0)
            <div class="alert alert-danger py-2 small rounded-3">Out of stock</div>
            @else
            <form action="{{ route('user.shop.order', $product) }}" method="POST" class="card border-0 shadow-sm rounded-4 p-4">
                @csrf
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Your Selling Price (৳) <span class="text-danger">*</span></label>
                        <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                               min="{{ $product->final_price }}" step="0.01" value="{{ old('selling_price', $product->final_price) }}" required>
                        <div class="form-text">Min ৳{{ number_format($product->final_price, 2) }} — anything above = your profit</div>
                        @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name') }}" required>
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Customer Phone <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                               value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">District <span class="text-danger">*</span></label>
                        <input type="text" name="district" class="form-control @error('district') is-invalid @enderror"
                               value="{{ old('district') }}" required>
                        @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Upazila <span class="text-danger">*</span></label>
                        <input type="text" name="upazila" class="form-control @error('upazila') is-invalid @enderror"
                               value="{{ old('upazila') }}" required>
                        @error('upazila')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Delivery Address <span class="text-danger">*</span></label>
                        <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                                  rows="2" required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Your Shop Name (optional)</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold">Additional Instruction</label>
                        <input type="text" name="additional_instruction" class="form-control" value="{{ old('additional_instruction') }}">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <div>
                        <small class="text-muted">Delivery charge: <strong>৳120</strong></small>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-shopping-cart me-2"></i>Place Order
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
