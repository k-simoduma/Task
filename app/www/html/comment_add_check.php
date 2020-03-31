<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/comment_manager.php';

date_default_timezone_set("Asia/Tokyo");

//SESSION変数から各パラメータ取得
$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

$ticket_id = $_POST['ticket-id'];
$content =  htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');

//入力チェック
if(($content == '') || ($content == null)){
    header('Location: http://localhost:8080:ticket_view.php?id=' . $ticket_id);
    exit;
}else{
        //コメント管理クラスのインスタンス
        $comment_manager = new \AppTask\CommentManager();
        //DateTimeクラスのインスタンス
        $datetime = new DateTime();

        //コメント追加
        $comment_manager->AddComment($project_id,$ticket_id,$member_id,$datetime->format('Y-m-d H:i:s'),$content);
        
        header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
        exit;
}