<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $category = (string) $request->query('category', '');
        $search = trim((string) $request->query('q', ''));
        abort_if($category !== '' && ! array_key_exists($category, NewsPost::CATEGORIES), 404);

        $posts = NewsPost::query()
            ->visibleToPublic()
            ->with('author:id,name')
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%');
            }))
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        $alerts = NewsPost::query()
            ->visibleToPublic()
            ->where('is_banner', true)
            ->with('author:id,name')
            ->orderByRaw("CASE severity WHEN 'critical' THEN 0 WHEN 'urgent' THEN 1 ELSE 2 END")
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('news.index', [
            'posts' => $posts,
            'alerts' => $alerts,
            'categories' => NewsPost::CATEGORIES,
            'category' => $category,
            'search' => $search,
        ]);
    }

    public function show(string $slug): View
    {
        $post = NewsPost::query()->visibleToPublic()->with('author:id,name')->where('slug', $slug)->firstOrFail();

        return view('news.show', [
            'post' => $post,
            'bodyHtml' => Str::markdown($post->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
        ]);
    }

    public function attachment(string $slug, int $attachment): Response
    {
        $post = NewsPost::query()->visibleToPublic()->where('slug', $slug)->firstOrFail();
        $file = $post->attachments[$attachment] ?? null;
        abort_unless(is_array($file) && Storage::disk('local')->exists($file['path'] ?? ''), 404);

        if (request()->boolean('download')) {
            return Storage::disk('local')->download($file['path'], $file['name']);
        }

        return Storage::disk('local')->response($file['path'], $file['name'], [
            'Content-Type' => $file['mime'],
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}