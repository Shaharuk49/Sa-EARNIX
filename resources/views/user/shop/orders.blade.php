@extends('layouts.user')
@section('title', 'My Orders - SA EarniX')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:52px;height:52px;background:var(--bs-info);flex-shrink:0">
            <i class="fas fa-box text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">My Orders</h4>
            <small class="text-muted">Track all your reseller orders</small>
        </div>
        <a href="{{ route('user.shop.index') }}" class="btn btn-outline-primary btn-sm rounded-pill ms-auto">
            <i class="fas fa-store me-1"></i>Shop More
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">No orders yet. Place your first order from the shop!</p>
            <a href="{{ route('user.shop.index') }}" class="btn btn-primary rounded-pill">Browse Products</a>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach($orders as $order)
            <div class="card border-0 shadow-sm rounded-4" data-reveal>
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:var(--bs-light)">
                            <i class="fas fa-box text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h6 class="fw-bold mb-0">{{ $order->product->name ?? 'Product Removed' }}</h6>
                                <span class="badge rounded-pill
                                    @if($order->status === 'delivered') bg-success
                                    @elseif($order->status === 'cancelled') bg-danger
                                    @elseif($order->status === 'processing') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="d-flex gap-3 flex-wrap mt-1">
                                <small class="text-muted"><i class="fas fa-user me-1"></i>{{ $order->customer_name }}</small>
                                <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $order->customer_phone }}</small>
                                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>{{ $order->district }}, {{ $order->upazila }}</small>
                            </div>
                            <div class="d-flex gap-3 flex-wrap mt-2">
                                <small><span class="text-muted">Qty:</span> <strong>{{ $order->quantity }}</strong></small>
                                <small><span class="text-muted">Selling:</span> <strong class="text-primary">৳{{ number_format($order->selling_price, 2) }}</strong></small>
                                <small><span class="text-muted">Profit:</span>
                                    <strong class="{{ $order->profit_status === 'released' ? 'text-success' : 'text-warning' }}">
                                        ৳{{ number_format($order->profit_amount, 2) }}
                                        <span class="fw-normal text-muted">({{ ucfirst($order->profit_status) }})</span>
                                    </strong>
                                </small>
                                <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
