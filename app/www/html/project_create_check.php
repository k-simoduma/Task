<?php
require_once __DIR__ . '/function/class/project_manager.php';

if (isset($_POST['create-btn'])) {

    $msg = '';
    $project_name = htmlspecialchars($_POST['project-name'], ENT_QUOTES, 'UTF-8');

    $project_manager = new \AppTask\ProjectManager();

    //入力されているかチェックする
    if ($project_name == '') {
        $msg = 'プロジェクト名が入力されていません。';
    } else {
        //すでに同名のプロジェクトが登録されていないかチェック
        if ($project_manager->GetProject($project_name) != false) {
            $msg = $project_name . 'は既に登録されています。';
        } else {
            //プロジェクト追加
            $project_manager->AddProject($project_name);
        }
    }
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
            if (($msg !== '') && ($msg !== null)) {
                echo '<span style="color: red;">' . $msg . '</span>';
                echo '<br/>';
                echo '<input type="button" onclick="history.back()" value="戻る">';
            } else {
                echo 'プロジェクトを作成しました。';
                echo '<br/>';
                echo $project_name;
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