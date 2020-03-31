<?php
session_start();

require_once __DIR__ . '/function/class/member_manager.php';

$err_msg = '';

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$mail_address = htmlspecialchars($_POST['mail-address'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
$password = md5($password);
$member_manager = new \AppTask\MemberManager();

//入力されたメンバーのレコードを取得
$rec = $member_manager->GetMember($project_id, $mail_address, $password);

//取得成功
if ($rec != false) {
    $_SESSION['login'] = 1;
    $_SESSION['member-id'] = $rec->id;
    $_SESSION['member-name'] = $rec->member_name;

    //初期化
    $_SESSION['filter-author'] = 0;
    $_SESSION['filter-charge'] = 0;
    $_SESSION['filter-status'] = 0;
    $_SESSION['filter-category'] = 0;
    $_SESSION['filter-priority'] = 0;
    $_SESSION['filter-word'] = '';

    header('Location: http://localhost:8080/my_page.php');
    exit;
} else {
    $err_msg = 'メールアドレスかパスワードが間違っています。' . $project_name;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-ログイン</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_01.css">
</head>

<body>
    <header>
        <div class="logo-link"><a href="index.php"><img src="image/task_logo.png" alt="Task"></a></div>
    </header>
    <main>
        <div class="contents">
            <?php
            if ($rec == false) {
                echo '<span style="color: red;">' . $err_msg . '</span>';
                echo '<br />';
                echo '<input type="button" onclick="history.back()" value="戻る">';
                exit;
            }
            ?>
    </main>
    <footer>
    </footer>
</body>

</html>