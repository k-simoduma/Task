<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-プロジェクト設定</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
</head>

<body>
    <header>
        <div class="logo-link"><a href="my_page.php"><img src="image/task_logo.png" alt="Task"></a></div>
        <div class="menu-bar">
            <li class="proj-name">プロジェクト名:<?php echo $project_name; ?></li>
            <li class="menu-item"><a href="ticket_add.php" title="新規チケットを追加">追加</a></li>
            <li class="menu-item"><a href="ticket_search.php" title="チケットを検索">検索</a></li>
            <li class="menu-item"><a href="project_setting.php" title="プロジェクト設定">設定</a></li>
            <li class="menu-item"><a href="project_logout.php" title="ログアウト">ログアウト</a></li>
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
    </main>
    <footer>
    </footer>
</body>

</html>