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

//DateTimeクラスのインスタンス
$datetime = new DateTime();

//カテゴリーID
$category_id = $_POST['category'];
//プライオリティID
$priority_id = $_POST['priority'];
//期限
$deadline = $_POST['deadline'];
//タイトル
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
//内容
$content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
//最終更新日
$last_updated = $datetime->format('Y-m-d H:i:s');

//入力が不備
if (($deadline == '') || ($title == '') && ($content == '')) {
    header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
    exit;
}else{
    //チケット管理クラスのインスタンス
    $ticket_manager = new \AppTask\TicketManeger();

    $ticket_manager->UpdateTicket($project_id,$member_id,$ticket_id,$last_updated,$category_id,$priority_id,$deadline,$title,$content);
    header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
    exit;
}