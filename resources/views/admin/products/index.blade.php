@extends('layout.admin')

@section('title', 'Products')

@section('content')
    <div class="admin-page-heading">
        <div><span class="eyebrow">CATALOGUE</span>
            <h1>Products</h1>
            <p>Manage the fragrances your customers will discover.</p>
        </div>
        <a class="button button-dark" href="{{ route('admin.products.create') }}">Add product +</a>
    </div>

    <div class="admin-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Sizes</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="table-product"><img
                                        src="{{ $product->image_path ?: asset('images/perfume.svg') }}" alt="">
                                    <div><strong>{{ $product->name }}</strong><small>{{ $product->brand }}</small></div>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->variants->count() }}</td>
                            <td><span
                                    class="status-pill {{ $product->is_published ? 'status-positive' : '' }}">{{ $product->is_published ? 'Published' : 'Draft' }}</span>
                            </td>
                            <td><a class="table-action" href="{{ route('admin.products.edit', $product) }}">Edit ↗</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">No products yet. Add your first fragrance.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('user.partials.pagination', ['paginator' => $products])
    </div>
@endsection
