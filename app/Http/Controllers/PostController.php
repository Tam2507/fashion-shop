<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Admin methods
    public function index()
    {
        $posts = Post::with('author')->ordered()->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'post_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'display_order' => 'required|integer|min:0',
        ]);

        $validated['slug'] = $request->slug ?: Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['author_id'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::create($validated);

        // Handle multiple post images
        if ($request->hasFile('post_images')) {
            foreach ($request->file('post_images') as $index => $image) {
                $path = $image->store('posts/images', 'public');
                $post->images()->create([
                    'image_path' => $path,
                    'position' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Đã tạo bài viết "' . $post->title . '" thành công!');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'post_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'display_order' => 'required|integer|min:0',
            'remove_images' => 'nullable|array',
            'remove_featured_image' => 'nullable|boolean',
        ]);

        $validated['slug'] = $request->slug ?: Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        // Handle featured image removal
        if ($request->input('remove_featured_image') == '1' && $post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
            $validated['featured_image'] = null;
        }

        // Handle new featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update($validated);

        // Handle image removal
        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $image = $post->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Handle new post images
        if ($request->hasFile('post_images')) {
            $maxPosition = $post->images()->max('position') ?? 0;
            foreach ($request->file('post_images') as $index => $image) {
                $path = $image->store('posts/images', 'public');
                $post->images()->create([
                    'image_path' => $path,
                    'position' => $maxPosition + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Đã cập nhật bài viết thành công!');
    }

    public function destroy(Post $post)
    {
        // Delete featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Đã xóa bài viết thành công!');
    }

    // Public methods
    public function publicIndex()
    {
        $posts = Post::published()->ordered()->paginate(12);
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->published()->firstOrFail();
        return view('posts.show', compact('post'));
    }
}
