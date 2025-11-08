<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleFilterRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Helpers\ApiResponseHelper;

class ArticlesController extends Controller
{
    public function index(ArticleFilterRequest $request)
    {
        $articles = Article::filter($request->validated())
            ->latest('published_at')
            ->paginate(10);

        return ApiResponseHelper::success(
            ArticleResource::collection($articles),
            'Articles retrieved successfully'
        );
    }

    /**
     * Display a single article by its ID or URL.
     */
    public function show(Request $request, $id = null)
    {
        // Check if "url" query parameter is present
        if ($request->has('url')) {
            $article = Article::where('url', $request->get('url'))->first();
        } else {
            $article = Article::find($id);
        }

        if (!$article) {
            return ApiResponseHelper::error('Article not found', 404);
        }

        return ApiResponseHelper::success(
            new ArticleResource($article),
            'Article retrieved successfully'
        );
    }
}
