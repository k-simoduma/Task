<?php
session_start();

require_once __DIR__ . '/function/class/project_manager.php';

$name = htmlspecialchars($_POST['project-name'], ENT_QUOTES, 'UTF-8');

$project_manager = new \AppTask\ProjectManager();

//入力されたプロジェクト名のレコードを取得
$rec = $project_manager->GetProject($name);

//取得成功
if ($rec != false) {
    $_SESSION['project-id'] = $rec->id;
    $_SESSION['project-name'] = $rec->project_name;
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
                $err_msg = $name . 'というプロジェクトが見つかりません。';
                echo '<span style="color: red;">' . $err_msg . '</span>';
                echo '<br />';
                echo '<input type="button" onclick="history.back()" value="戻る">';
                exit;
            }
            ?>

            <div class="login-form">
                <div class="project-login">
                    <div class="form-title">
                        <h2>ログイン</h2>
                    </div>
                    <form action="project_login_check.php" method="post">
                        <div class="form-item">
                            <span id="project-name"><?php echo $rec->project_name; ?></span>
                        </div>
                        <div class="form-item">
                            <input id="input-mail-address" type="text" name="mail-address" placeholder="メールアドレス">
                        </div>
                        <div class="form-item">
                            <input id="input-password" type="password" name="password" placeholder="パスワード">
                        </div>
                        <div class="form-item">
                            <input id="login-button" type="submit" name="login-btn" value="ログイン">
                        </div>
                        <div class="form-item">
                            <a href="member_create.php">新しくメンバーを追加する</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>

</html>