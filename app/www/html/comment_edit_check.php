<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/comment_manager.php';

$ticket_id = $_POST['ticket-id'];
$comment_id = $_POST['comment-id'];
$comment_content = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');

if(($comment_content == '') || ($comment_content == null)){
    header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
    exit;
}

$comment_manager = new \AppTask\CommentManager();
$comment_manager->UpdateComment($comment_id,$comment_content);

header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
exit;