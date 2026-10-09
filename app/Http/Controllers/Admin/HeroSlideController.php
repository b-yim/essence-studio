<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero-slides', [
            'heroSlides' => HeroSlide::query()->ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedSlide($request);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')?->storePublicly('hero-slides', 'public');

            if (! is_string($imagePath)) {
                return back()->withErrors(['image' => 'The hero image could not be stored.'])->withInput();
            }

            $data['image_path'] = $imagePath;
            $data['image_url'] = null;
        } else {
            $data['image_path'] = null;
        }

        HeroSlide::create($data);

        return back()->with('success', 'Hero slide added.');
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $this->validatedSlide($request, $heroSlide);

        if ($heroSlide->is_active && ! $data['is_active'] && $this->isLastActiveSlide($heroSlide)) {
            return back()->withErrors(['is_active' => 'At least one hero slide must remain visible.']);
        }

        $previousImagePath = $heroSlide->image_path;
        $previousImageWasUploaded = $heroSlide->usesUploadedImage();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')?->storePublicly('hero-slides', 'public');

            if (! is_string($imagePath)) {
                return back()->withErrors(['image' => 'The hero image could not be stored.'])->withInput();
            }

            $data['image_path'] = $imagePath;
            $data['image_url'] = null;
        } elseif (array_key_exists('image_url', $data)) {
            $data['image_path'] = null;
        }

        $heroSlide->update($data);

        $imageWasReplaced = $request->hasFile('image') || array_key_exists('image_url', $data);

        if ($imageWasReplaced && $previousImageWasUploaded && is_string($previousImagePath)) {
            Storage::disk('public')->delete($previousImagePath);
        }

        return back()->with('success', 'Hero slide updated.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        if ($heroSlide->is_active && $this->isLastActiveSlide($heroSlide)) {
            return back()->withErrors(['hero_slide' => 'At least one hero slide must remain visible.']);
        }

        $imagePath = $heroSlide->image_path;
        $imageWasUploaded = $heroSlide->usesUploadedImage();

        $heroSlide->delete();

        if ($imageWasUploaded && is_string($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        return back()->with('success', 'Hero slide deleted.');
    }

    /** @return array<string, mixed> */
    private function validatedSlide(Request $request, ?HeroSlide $heroSlide = null): array
    {
        $data = $request->validate([
            'kicker' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'image_alt' => ['required', 'string', 'max:255'],
            'image_position' => ['required', Rule::in(['center', 'top', 'bottom', 'left', 'right'])],
            'sort_order' => ['required', 'integer', 'min:1', 'max:999'],
            'image' => [
                $heroSlide ? 'nullable' : 'required_without:image_url',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'image_url' => [
                $heroSlide ? 'nullable' : 'required_without:image',
                'url',
                'starts_with:http://,https://',
                'max:2048',
            ],
        ]);

        unset($data['image']);

        if ($heroSlide && ! $request->filled('image_url')) {
            unset($data['image_url']);
        }

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function isLastActiveSlide(HeroSlide $heroSlide): bool
    {
        return HeroSlide::query()
            ->active()
            ->where('id', '!=', $heroSlide->id)
            ->doesntExist();
    }
}
