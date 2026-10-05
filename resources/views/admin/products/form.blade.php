@extends('layout.admin')

@php($editing = $product->exists)
@section('title', $editing ? 'Edit product' : 'Add product')

@section('content')
    <div class="admin-page-heading">
        <div><a class="back-link" href="{{ route('admin.products.index') }}">← Products</a>
            <h1>{{ $editing ? 'Edit fragrance' : 'Add a fragrance' }}</h1>
            <p>Give customers the details they need to find their scent.</p>
        </div>
    </div>

    <form method="post" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
        class="admin-product-form">
        @csrf
        @if ($editing)
            @method('PATCH')
        @endif
        <div class="admin-panel">
            <h2>Product basics</h2>
            <div class="form-grid">
                <div class="field"><label for="name">Product name</label><input id="name" name="name"
                        value="{{ old('name', $product->name) }}" required></div>
                <div class="field"><label for="brand">Brand</label><input id="brand" name="brand"
                        value="{{ old('brand', $product->brand) }}" required></div>
            </div>
            <div class="field"><label for="category_id">Category</label><select id="category_id" name="category_id"
                    required>
                    <option value="">Choose a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select></div>
            <div class="field"><label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <div class="admin-panel">
            <h2>Fragrance profile</h2>
            <div class="form-grid">
                @foreach ([
            'style' => 'Inspired by / style',
            'opening_smell' => 'Opening smell',
            'main_vibe' => 'Main vibe',
            'character' => 'Character',
            'overall_smell' => 'Overall smell',
            'best_seasons' => 'Best seasons',
            'use_cases' => 'Use cases',
            'longevity' => 'Longevity',
            'projection' => 'Projection',
        ] as $field => $label)
                    <div class="field"><label for="{{ $field }}">{{ $label }}</label><input
                            id="{{ $field }}" name="{{ $field }}"
                            value="{{ old($field, $product->$field) }}"></div>
                @endforeach
            </div>
        </div>

        <div class="admin-panel">
            <h2>Images and visibility</h2>
            <div class="field"><label for="image_path">Main image URL</label><input id="image_path" name="image_path"
                    type="url"
                    value="{{ old('image_path', str_starts_with($product->image_path ?? '', 'http') ? $product->image_path : '') }}"
                    placeholder="https://..."><small>Leave blank to use the studio illustration.</small></div>
            <div class="field"><label for="image_alt">Image description</label><input id="image_alt" name="image_alt"
                    value="{{ old('image_alt', $product->image_alt) }}" placeholder="Describe the product image"></div>
            <div class="field"><label for="gallery_urls">Gallery image URLs</label>
                <textarea id="gallery_urls" name="gallery_urls" rows="4" placeholder="One image URL per line">{{ old('gallery_urls', implode("\n", $product->gallery ?? [])) }}</textarea>
            </div>
            <label class="checkbox-row"><input type="checkbox" name="is_published" value="1"
                    @checked(old('is_published', $product->is_published))> Published and visible in the shop</label>
        </div>

        @unless ($editing)
            <div class="admin-panel">
                <h2>First size</h2>
                <p>Add one sellable size now. You can add more sizes after saving.</p>
                <div class="form-grid">
                    <div class="field"><label for="sku">SKU</label><input id="sku" name="sku"
                            value="{{ old('sku') }}" required></div>
                    <div class="field"><label for="size">Size</label><input id="size" name="size"
                            value="{{ old('size') }}" placeholder="100 ml" required></div>
                    <div class="field"><label for="price">Price in USD</label><input id="price" name="price"
                            type="number" min="0.01" step="0.01" value="{{ old('price') }}" required></div>
                    <div class="field"><label for="sale_price">Sale price <span class="muted">(optional)</span></label><input
                            id="sale_price" name="sale_price" type="number" min="0.01" step="0.01"
                            value="{{ old('sale_price') }}"></div>
                    <div class="field"><label for="stock_quantity">Stock quantity</label><input id="stock_quantity"
                            name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', 0) }}"
                            required></div>
                </div>
            </div>
        @endunless

        <div class="form-actions"><button class="button button-dark"
                type="submit">{{ $editing ? 'Save changes' : 'Create product' }}</button></div>
    </form>

    @if ($editing)
        <section class="admin-panel admin-variants">
            <h2>Sizes and stock</h2>
            @foreach ($product->variants as $variant)
                <div class="variant-row">
                    <form method="post" action="{{ route('admin.variants.update', [$product, $variant]) }}"
                        class="variant-form">
                        @csrf @method('PATCH')
                        <div class="field"><label>SKU</label><input name="sku" value="{{ $variant->sku }}"
                                required>
                        </div>
                        <div class="field"><label>Size</label><input name="size" value="{{ $variant->size }}"
                                required></div>
                        <div class="field"><label>Price</label><input name="price" type="number" min="0.01"
                                step="0.01" value="{{ number_format($variant->price_cents / 100, 2, '.', '') }}"
                                required></div>
                        <div class="field"><label>Sale price</label><input name="sale_price" type="number"
                                min="0.01" step="0.01"
                                value="{{ $variant->sale_price_cents ? number_format($variant->sale_price_cents / 100, 2, '.', '') : '' }}">
                        </div>
                        <div class="field"><label>Stock</label><input name="stock_quantity" type="number"
                                min="0" value="{{ $variant->stock_quantity }}" required></div>
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1"
                                @checked($variant->is_active)> Active</label>
                        <button class="button button-small button-outline" type="submit">Save</button>
                    </form>
                    <form method="post" action="{{ route('admin.variants.destroy', [$product, $variant]) }}"
                        onsubmit="return confirm('Delete this size?')">@csrf @method('DELETE')<button
                            class="text-button text-danger" type="submit">Delete</button></form>
                </div>
            @endforeach
            <h3>Add another size</h3>
            <form method="post" action="{{ route('admin.variants.store', $product) }}" class="variant-form">
                @csrf
                <div class="field"><label for="new_sku">SKU</label><input id="new_sku" name="sku" required>
                </div>
                <div class="field"><label for="new_size">Size</label><input id="new_size" name="size"
                        placeholder="50 ml" required></div>
                <div class="field"><label for="new_price">Price</label><input id="new_price" name="price"
                        type="number" min="0.01" step="0.01" required></div>
                <div class="field"><label for="new_sale_price">Sale price</label><input id="new_sale_price"
                        name="sale_price" type="number" min="0.01" step="0.01"></div>
                <div class="field"><label for="new_stock">Stock</label><input id="new_stock" name="stock_quantity"
                        type="number" min="0" value="0" required></div>
                <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" checked>
                    Active</label>
                <button class="button button-small button-dark" type="submit">Add size</button>
            </form>
        </section>
        <form method="post" action="{{ route('admin.products.destroy', $product) }}"
            onsubmit="return confirm('Delete this product?')" class="danger-zone">@csrf @method('DELETE')<button
                class="text-button text-danger" type="submit">Delete product</button></form>
    @endif
@endsection
