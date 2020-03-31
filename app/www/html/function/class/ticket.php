<?php

namespace AppTask;

//チケットクラス
class Ticket
{
    //プロジェクトID
    public $project_id;

    //作成者ID
    public $author_id;

    //担当者ID
    public $charge_id;

    //追加日時
    public $add_date;

    //最終更新日時
    public $last_updated;

    //状態ID
    public $status_id;

    //カテゴリーID
    public $category_id;

    //優先度ID
    public $priority_id;

    //期限
    public $deadline;

    //タイトル
    public $title;

    //内容
    public $content;

    //プロパティを配列に変換
    public function ToArray()
    {
        return (array) $this;
    }
}
