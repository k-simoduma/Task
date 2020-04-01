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

if (isset($_GET['id'])){
    $ticket_id = $_GET['id'];
    
    $ticket_manager = new \AppTask\TicketManeger();
    $rec_ticket = $ticket_manager->GetTicket($ticket_id);
    $rec_category = $ticket_manager->GetAllCategory();
    $rec_priority = $ticket_manager->GetAllPrioriry();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-チケット編集</title>
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
            <div class="ticket-edit-form">
                <form action="ticket_edit_check.php" method="post">
                    <table class="form-table">
                        <h4 class="form-title">■チケット編集</h4>
                        <tbody class="form-input">
                            <tr>
                                <th class="form-header"><span>ID</span></th>
                                <td class="form-item"><span id="ticket-id"><?php echo $rec_ticket->id; ?></span></td>
                                <?php echo '<input type="hidden" name="ticket-id" value="' . $rec_ticket->id . '">' ?>
                            </tr>
                            <tr>
                                <th class="form-header"><span>プロジェクト名</span></th>
                                <td class="form-item" colspan="6"><span id="project-name"><?php echo $project_name; ?></span></td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>カテゴリ</span></th>
                                <td class="form-item">
                                    <select id="ticket-category" name="category">
                                        <?php
                                        foreach($rec_category as $category){
                                            if($category->id == $rec_ticket->category_id){
                                                echo '<option value="'.$category->id.'" selected>'.$category->category_name.'</option>';
                                            }else{
                                                echo '<option value="'.$category->id.'">'.$category->category_name.'</option>';
                                            }
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
                                        foreach($rec_priority as $priority){
                                            if($priority->id == $rec_ticket->priority_id){
                                                echo '<option value="'.$priority->id.'" selected>'.$priority->priority_name.'</option>';
                                            }else{
                                                echo '<option value="'.$priority->id.'">'.$priority->priority_name.'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>期限</span></th>
                                <td class="form-item">
                                    <label class="date-label">
                                        <?php
                                        echo '<input id="ticket-deadline" type="date" name="deadline" value="'.$rec_ticket->deadline.'">';
                                        ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>チケット名</span></th>
                                <td class="form-item" colspan="6">
                                    <?php
                                    echo '<input id="ticket-title" type="text" name="title" value="'.$rec_ticket->ticket_title.'">';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="form-header"><span>内容</span></th>
                                <td class="form-item" colspan="6">
                                    <textarea id="ticket-content" name="content" cols="56" rows="20"><?php echo $rec_ticket->ticket_content; ?></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-button"><input id="done-button" type="submit" name="add-btn" value="完了"></div>
                </form>
            </div>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>