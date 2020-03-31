<?php
session_start();

require_once __DIR__ . '/function/class/member_manager.php';

if (isset($_POST['create-btn'])) {
    $err_flag = false;
    $err_msg = '';

    $project_id = $_SESSION['project-id'];
    $member_name = htmlspecialchars($_POST['member-name'], ENT_QUOTES, 'UTF-8');
    $mail_address = htmlspecialchars($_POST['mail-address'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $password = md5($password);

    $member_manager = new \AppTask\MemberManager();

    if ($member_name == '') {
        $err_flag = true;
        $err_msg = '名前を入力してください。';
    }

    if ($mail_address == '') {
        $err_flag = true;
        $err_msg = 'メールアドレスを入力してください。';
    }

    if ($password == '') {
        $err_flag = true;
        $err_msg = 'パスワードを入力してください。';
    }

    $member_manager->AddMembar($project_id, $member_name, $mail_address, $password);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>task-プロジェクト作成</title>
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
            if ($err_flag) {
                echo '<span style="color: red;">' . $err_msg . '</span>';
                echo '<br/>';
                echo '<input type="button" onclick="history.back()" value="戻る">';
            } else {
                echo 'メンバーを作成しました。';
                echo '<br/>';
                echo $member_name;
                echo '<br/>';
                echo '<form action="index.php" method="post">';
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