<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        // Get Articles
        $articles = Article::orderBy('created_at', 'desc')->paginate(2);

        // Return collection of articles as a resource
        return ArticleResource::collection($articles);
    }

    public function show($id)
    {
        // Get single Article
        $article = Article::findOrFail($id);

        // Return single of article as a resource
        return new ArticleResource($article);
    }

    public function store(Request $request)
    {
        // Store existing Article or store a new one
        $article = $request->isMethod('put') ? Article::findOrFail($request->article['article_id']) : new Article;

        //var_dump($request->article);exit;

        //$article->id = $request->input('article_id');
        //$article->title = $request->input('title');
        //$article->body = $request->input('body');

        $article->id = $request->article['article_id'];
        $article->title = $request->article['title'];
        $article->body = $request->article['body'];

        if($article->save())
        {
            return new ArticleResource($article);
        }
    }

    public function destroy(Request $request, $id)
    {
        // Get single Article
        $article = Article::findOrFail($id);

        if($article->delete())
        {
            return new ArticleResource($article);
        }
    }
}
