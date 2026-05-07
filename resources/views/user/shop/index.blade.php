@extends('layouts.user')
@section('title', 'Shop - SA EarniX')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:52px;height:52px;background:var(--bs-warning);flex-shrink:0">
            <i class="fas fa-store text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Shop</h4>
            <small class="text-muted">Buy & resell products — earn profit on every sale</small>
        </div>
        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill ms-auto">
            <i class="fas fa-box me-1"></i>My Orders
        </a>
    </div>

    {{-- Category Filter --}}
    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="{{ route('user.shop.index') }}"
           class="btn btn-sm rounded-pill {{ !request('category') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
        @foreach($categories as $cat)
        <a href="{{ route('user.shop.index', ['category' => $cat->id]) }}"
           class="btn btn-sm rounded-pill {{ request('category') == $cat->id ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
            <p class="text-muted">No products available right now. Check back soon!</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($products as $product)
            @php $img = $product->media->where('type','image')->first(); @endphp
            <div class="col-6 col-md-4 col-lg-3" data-reveal>
                <a href="{{ route('user.shop.show', $product) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                        @if($img)
                        <img src="{{ asset('storage/' . $img->file_path) }}"
                             class="card-img-top rounded-top-4" style="height:170px;object-fit:cover" alt="{{ $product->name }}">
                        @else
                        <div class="rounded-top-4 d-flex align-items-center justify-content-center"
                             style="height:170px;background:var(--bs-light)">
                            <i class="fas fa-box-open fa-2x text-muted"></i>
                        </div>
                        @endif
                        <div class="card-body p-3">
                            <p class="fw-semibold text-dark small mb-1 line-clamp-2">{{ $product->name }}</p>
                            <p class="text-muted x-small mb-1 small">{{ $product->category->name ?? '' }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-primary">৳{{ number_format($product->final_price, 0) }}</span>
                                @if($product->stock > 0)
                                <span class="badge bg-success-subtle text-success small">In Stock</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger small">Out</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
