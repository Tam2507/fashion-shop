<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\ImageUploadService;
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
            $validated['featured_image'] = (new ImageUploadService)->upload($request->file('featured_image'), 'posts');
        }

        $post = Post::create($validated);

        if ($request->hasFile('post_images')) {
            $svc = new ImageUploadService;
            foreach ($request->file('post_images') as $index => $image) {
                $path = $svc->upload($image, 'posts/images');
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

        if ($request->input('remove_featured_image') == '1' && $post->featured_image) {
            (new ImageUploadService)->delete($post->featured_image);
            $validated['featured_image'] = null;
        }

        if ($request->hasFile('featured_image')) {
            (new ImageUploadService)->delete($post->featured_image);
            $validated['featured_image'] = (new ImageUploadService)->upload($request->file('featured_image'), 'posts');
        }

        $post->update($validated);

        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $image = $post->images()->find($imageId);
                if ($image) {
                    (new ImageUploadService)->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('post_images')) {
            $svc = new ImageUploadService;
            $maxPosition = $post->images()->max('position') ?? 0;
            foreach ($request->file('post_images') as $index => $image) {
                $path = $svc->upload($image, 'posts/images');
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
        if ($post->featured_image) {
            (new ImageUploadService)->delete($post->featured_image);
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
        $post = Post::where('slug', $slug)
            ->published()
            ->with(['comments.user'])
            ->firstOrFail();
        return view('posts.show', compact('post'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'guest_name' => 'required_unless:user_id,!=,null|string|max:255',
            'guest_email' => 'required_unless:user_id,!=,null|email|max:255',
        ]);

        $commentData = [
            'post_id' => $post->id,
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'status' => 'approved', // Auto approve
        ];

        if (auth()->check()) {
            $commentData['user_id'] = auth()->id();
        } else {
            $commentData['guest_name'] = $validated['guest_name'];
            $commentData['guest_email'] = $validated['guest_email'];
        }

        \App\Models\PostComment::create($commentData);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được đăng!');
    }
}
