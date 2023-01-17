<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostComment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;

Carbon::setLocale('ja');

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    // ＝＝＝＝＝ホーム画面の表示＝＝＝＝＝
    public function index()
    {
        // 投稿内容の取得表示
        $posts = Post::select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            // deleted_atがNULLのデータを表示
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        // 投稿とコメントの紐付いてる情報の取得
        $post_comments = Post::select('posts.id', 'comments.id as comment_id', 'comments.deleted_at')
            ->leftJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')
            ->leftJoin('comments', 'post_comments.comment_id', '=', 'comments.id')
            ->whereNull('comments.deleted_at')
            ->whereNotNull('comments.id')
            ->orderBy('id', 'DESC')
            ->get();


        return view('home', compact('posts', 'post_comments'));
    }



    // ＝＝＝＝＝新規投稿画面への遷移＝＝＝＝＝
    public function create()
    {
        return view('create');
    }



    // ＝＝＝＝＝新規投稿画面の投稿＝＝＝＝＝
    public function store(Request $request)
    {
        // 新規投稿をDBに挿入
        $posts = $request->all();

        // 投稿のバリデーション
        $request->validate(['content' => 'required']);

        Post::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);
        return redirect(route('home'));
    }



    // ＝＝＝＝＝編集画面の遷移と投稿内容表示＝＝＝＝＝
    public function edit($id, Post $post)
    {
        // ログインユーザ以外の他ユーザの編集画面遷移のバリデイト
        $post = Post::find($id);
        if(\Auth::id() !== $post->user_id){
            return redirect()->route('home');
        }else{
            // idに一致する内容の取得
            $edit_post = Post::find($id);
    
            return view('edit', compact('edit_post'));

        }
    }



    // ＝＝＝＝＝編集画面の編集実行＝＝＝＝＝
    public function update(Request $request, Post $post)
    {
        // 投稿編集内容をDBで更新
        $posts = $request->all();
        // 投稿のバリデーション
        $request->validate(['content' => 'required']);

        Post::where('id', $posts['post_id'])->update(['content' => $posts['content']]);
        return redirect(route('home'));
    }



    // ＝＝＝＝＝編集画面の削除＝＝＝＝＝
    public function destroy(Request $request, Post $post)
    {
        // 投稿編集内容をDBで論理削除
        $posts = $request->all();
        Post::where('id', $posts['post_id'])->update(['deleted_at' => Carbon::now()]);
        return redirect(route('home'));
    }



    // ＝＝＝＝＝返信画面表示＝＝＝＝＝
    public function reply($id)
    {
        // idに一致する内容の取得
        $reply_post = Post::find($id);
        // 返信相手のユーザネーム取得
        $reply_user = Post::select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $reply_post['id'])
            ->get();

        return view('reply', compact('reply_post', 'reply_user'));
    }



    // ＝＝＝＝＝返信画面の返信実行＝＝＝＝＝
    public function comment(Request $request)
    {
        // 投稿編集内容をDBで更新
        $comment = $request->all();

        // 投稿のバリデーション
        $request->validate(['content' => 'required']);

        $comment_id = Comment::insertGetId(['user_id' => \Auth::id(), 'content' => $comment['content']]);
        PostComment::insert(['post_id' => $comment['post_id'], 'comment_id' => $comment_id]);

        $post_content = Post::find($comment['post_id']);

        // 投稿内容の取得表示
        $posts = Post::select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $post_content['id'])
            // deleted_atがNULLのデータを表示
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        // 返信内容の取得表示
        $comments = Comment::select('comments.*', 'post_comments.*', 'users.id', 'users.name')
            ->join('post_comments', 'comments.id', '=', 'post_comments.comment_id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->whereNull('deleted_at')
            ->orderBy('comments.created_at', 'ASC')
            ->get();

        return view('post', compact('post_content', 'posts', 'comments'));
    }


    // ＝＝＝＝＝投稿詳細画面表示＝＝＝＝＝
    public function post($id)
    {
        // idに一致する内容の取得
        $post_content = Post::find($id);

        // 投稿内容の取得表示
        $posts = Post::select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $post_content['id'])
            // deleted_atがNULLのデータを表示
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        // 返信内容の取得表示
        $comments = Comment::select('comments.*', 'post_comments.*', 'users.id', 'users.name')
            ->join('post_comments', 'comments.id', '=', 'post_comments.comment_id')
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->whereNull('deleted_at')
            ->orderBy('comments.created_at', 'ASC')
            ->get();

        // 投稿とコメントの紐付いてる情報の取得
        $post_comments = Post::select('posts.id', 'comments.id as comment_id', 'comments.deleted_at')
            ->leftJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')
            ->leftJoin('comments', 'post_comments.comment_id', '=', 'comments.id')
            ->whereNull('comments.deleted_at')
            ->whereNotNull('comments.id')
            ->orderBy('id', 'DESC')
            ->get();

        return view('post', compact('post_content', 'posts', 'comments','post_comments'));
    }



    //  個人ページ
    public function account($id)
    {
        // 投稿内容の取得表示
        $posts = Post::select('posts.*', 'users.name')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.user_id', '=', $id)
            // deleted_atがNULLのデータを表示
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        // 投稿とコメントの紐付いてる情報の取得
        $post_comments = Post::select('posts.id', 'comments.id as comment_id', 'comments.deleted_at')
            ->leftJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')
            ->leftJoin('comments', 'post_comments.comment_id', '=', 'comments.id')
            ->whereNull('comments.deleted_at')
            ->whereNotNull('comments.id')
            ->orderBy('id', 'DESC')
            ->get();

        return view('account', compact('posts', 'post_comments'));
    }
}
