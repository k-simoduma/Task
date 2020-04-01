<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/comment_manager.php';

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

$ticket_id = $_POST['ticket-id'];
$comment_id = $_POST['comment-id'];

$comment_manager = new \AppTask\CommentManager();
$rec_comment = $comment_manager->GetComment($comment_id);

if($rec_comment == false){
    header('Location: http://localhost:8080/ticket_view.php?id=' . $ticket_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-コメント編集</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_04.css">
</head>
<body>
    <header>
        <div class="logo-link"><a href="my_page.php"><img src="image/task_logo.png" alt="Task"></a></div>
        <div class="menu-bar">
            <li class="proj-name">プロジェクト名:<?php echo $project_name; ?></li>
            <li class="menu-item"><a href="ticket_add.php">追加</a></li>
            <li class="menu-item"><a href="ticket_search.php">検索</a></li>
            <li class="menu-item"><a href="project_setting.php">設定</a></li>
            <li class="menu-item"><a href="project_logout.php">ログアウト</a></li>
        </div>
    </header>
    <main>
        <div class="user-name">ユーザー:<?php echo $member_name; ?></div>
        <div class="jump-form">
            <form action="ticket_jump_check.php" method="post">
                <input id="input-ticket-num" type="text" name="ticket-num">
                <input id="jump-button" type="submit" name="jump-btn" value="ジャンプ">
            </form>
        </div>
        <div class="contents">
            <div class="block">
                <h4 class="block-title">■コメント編集</h4>
                <form action="comment_edit_check.php" method="post">
                    <?php echo '<input type="hidden" name="ticket-id" value="' . $ticket_id . '">' ?>
                    <?php echo '<input type="hidden" name="comment-id" value="' . $rec_comment->id . '">' ?>
                    <div class="form-item">
                        <textarea id="input-comment" name="comment" cols="30" rows="10"><?php echo $rec_comment->comment_content; ?></textarea>
                    </div>
                    <div class="form-item"><input id="input-done-button" type="submit" name="add-btn" value="完了"></div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        
    </footer>
</body>
</html>