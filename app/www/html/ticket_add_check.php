<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/ticket_manager.php';
require_once __DIR__ . '/function/class/ticket.php';

date_default_timezone_set("Asia/Tokyo");

$err_flag = false;
$err_msg = '';

//SESSION変数から各パラメータ取得
$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

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

//入力が不備
if (($deadline == '') || ($title == '') && ($content == '')) {
    $err_flag = true;
    $err_msg = '入力が完了していません。';
} else {
    //チケット管理クラスのインスタンス
    $ticket_manager = new \AppTask\TicketManeger();
    //チケットクラスのインスタンス
    $ticket = new \AppTask\Ticket();
    //DateTimeクラスのインスタンス
    $datetime = new DateTime();

    //プロジェクトIDを追加
    $ticket->project_id = $project_id;
    //作成者IDを追加
    $ticket->author_id = $member_id;
    //担当者はなしで追加
    $ticket->charge_id = '0';
    //登録日時を追加
    $ticket->add_date = $datetime->format('Y-m-d H:i:s');
    //最終更新日を追加
    $ticket->last_updated = $datetime->format('Y-m-d H:i:s');
    //状態IDを追加
    $ticket->status_id = $ticket_manager->GetStatusID('new');
    //カテゴリーIDを追加
    $ticket->category_id = $category_id;
    //優先度IDを追加
    $ticket->priority_id = $priority_id;
    //期限を追加
    $ticket->deadline = $deadline;
    //タイトルを追加
    $ticket->title = $title;
    //内容を追加
    $ticket->content = $content;

    //新規チケットを追加
    $ticket_manager->AddTicket($ticket);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-チケット追加</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_01.css">
</head>

<body>
    <header>
        <div class="logo-link"><a href="my_page.php"><img src="image/task_logo.png" alt="Task"></a></div>
        <div class="menu-bar">
            <li class="proj-name">プロジェクト名:<?php echo $project_name; ?></li>
            <li class="menu-item"><a href="ticket_add.php">追加</a></li>
            <li class="menu-item"><a href="ticket-search.php">検索</a></li>
            <li class="menu-item"><a href="ticket-search.php">設定</a></li>
            <li class="menu-item"><a href="ticket-search.php">ログアウト</a></li>
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
            <?php
            if ($err_flag == true) {
                echo '<span style="color: red;">' . $err_msg . '</span>';
                echo '<br/>';
                echo '<input type="button" onclick="history.back()" value="戻る">';
            } else {
                echo 'チケットを追加しました。';
                echo '<br/>';
                echo '<form action="my_page.php" method="post">';
                echo '<br/>';
                echo '<input type="submit" name="to-home" value="OK">';
                echo '<br/>';
                echo '</form>';
            }
            ?>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>