<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = DB::table('articles')->get();

        return view('articles.index')->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $validated = $request->validated();
        $validated['body'] = json_encode(explode(PHP_EOL, str_replace("\r", '', $validated['body'])));
        $validated['author_id'] = Auth::user()->id;
        Db::table('articles')->insert($validated);

        return redirect()->route('articles.index')->with('success', trans('message.article.store.success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = DB::table('articles')->find($id);

        return view('articles.show')->with('article', $article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $article = DB::table('articles')->where('id', $id)->get();
        $input = $article->first();
        $input->body = trim(implode(PHP_EOL, json_decode($input->body)));


        return view('articles.edit')->with('article', $input);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, int $id)
    {
        $validated = $request->validated();
        $validated['body'] = json_encode(explode(PHP_EOL, str_replace("\r", '', $validated['body'])));
        DB::table('articles')->where('id', $id)->update($validated);

        return redirect()->route('articles.show', ['article' => $id])->with('success', trans('message.article.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::table('articles')->delete($id);

        return redirect()->route('articles.index')->with('success', trans('message.article.delete.success'));
    }
}
