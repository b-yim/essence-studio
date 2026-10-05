@extends('layout.admin')

@section('title', 'Categories')

@section('content')
    <div class="admin-page-heading">
        <div><span class="eyebrow">CATALOGUE</span>
            <h1>Categories</h1>
            <p>Organise fragrances so customers can browse with ease.</p>
        </div>
    </div>
    <div class="admin-columns">
        <div class="admin-panel">
            <h2>All categories</h2>
            @forelse($categories as $category)
                <div class="category-row">
                    <form method="post" action="{{ route('admin.categories.update', $category) }}" class="category-edit">
                        @csrf @method('PATCH')
                        <input name="name" value="{{ $category->name }}" aria-label="Category name" required>
                        <input name="description" value="{{ $category->description }}" aria-label="Category description"
                            placeholder="Description">
                        <small>{{ $category->products_count }} products</small>
                        <button class="button button-small button-outline" type="submit">Save</button>
                    </form>
                    <form method="post" action="{{ route('admin.categories.destroy', $category) }}"
                        onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button
                            class="text-button text-danger" type="submit">Delete</button></form>
                </div>
            @empty
                <div class="empty-state">No categories yet.</div>
            @endforelse
        </div>
        <aside class="admin-panel">
            <h2>Add category</h2>
            <form method="post" action="{{ route('admin.categories.store') }}" class="stack-form">@csrf<div class="field">
                    <label for="name">Name</label><input id="name" name="name" value="{{ old('name') }}"
                        required>
                </div>
                <div class="field"><label for="description">Description</label>
                    <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div><button class="button button-dark" type="submit">Create category</button>
            </form>
        </aside>
    </div>
@endsection
