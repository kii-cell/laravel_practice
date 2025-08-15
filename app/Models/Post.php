<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;

    // savePostメソッドで個別にプロパティを設定するため、$fillableは必須ではありませんが、
    // create()など他のLaravelの機能を使う場合に備えて残しておくと良いでしょう。
    protected $fillable = ['title', 'body', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * 投稿データをモデルに設定し、保存するカスタムメソッド
     *
     * @param \Illuminate\Http\Request $request 送信されたリクエストオブジェクト
     * @return void
     */
    public function savePost(Request $request)
    {
        // $request オブジェクトから直接データを取得し、モデルのプロパティに割り当てる
        $this->title = $request->input('title');
        $this->body = $request->input('body');

        // published_at は nullable なので、空文字列の場合には null を設定
        $this->published_at = !empty($request->input('published_at')) ? $request->input('published_at') : null;

        // 登録処理
        $this->save();
    }
}
