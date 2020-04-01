<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/ticket_manager.php';

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

$ticket_manager = new \AppTask\TicketManeger();
$categorys = $ticket_manager->GetAllCategory();
$prioritys = $ticket_manager->GetAllPrioriry();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-チケット追加</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_03.css">
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
            <div class="ticket-add-form">
                <form action="ticket_add_check.php" method="post">
                    <table class="form-table">
                        <h4 class="form-title">■新規チケット追加</h4>
                        <tbody class="form-input">
                            <tr>
                                <th class="form-header"><span>プロジェクト名</span></th>
                                <td class="form-item" colspan="6"><span id="project-name"><?php echo $project_name; ?></span></td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>カテゴリ</span></th>
                                <td class="form-item">
                                    <select id="ticket-category" name="category">
                                        <?php
                                        foreach ($categorys as $category) {
                                            echo '<option value="'.$category->id.'">'.$category->category_name.'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>優先度</span></th>
                                <td class="form-item">
                                    <select id="ticket-priority" name="priority">
                                        <?php
                                        foreach ($prioritys as $priority) {
                                            echo '<option value="'.$priority->id.'">'.$priority->priority_name.'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>期限</span></th>
                                <td class="form-item"><label class="date-label"><input id="ticket-deadline" type="date" name="deadline"></label></td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>チケット名</span></th>
                                <td class="form-item" colspan="6"><input id="ticket-title" type="text" name="title"></td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>内容</span></th>
                                <td class="form-item" colspan="6"><textarea id="ticket-content" name="content" cols="56" rows="20"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-button"><input id="add-button" type="submit" name="add-btn" value="追加"></div>
                </form>
            </div>
        </div>
    </main>
    <footer>
    </footer>
</body>

</html>