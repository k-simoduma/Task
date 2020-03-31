<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    header('Location: http://localhost:8080/index.php');
    exit;
}

require_once __DIR__ . '/function/class/ticket_manager.php';
require_once __DIR__ . '/function/class/member_manager.php';
require_once __DIR__ . '/function/class/comment_manager.php';

$project_id = $_SESSION['project-id'];
$project_name = $_SESSION['project-name'];
$member_id = $_SESSION['member-id'];
$member_name = $_SESSION['member-name'];

if (isset($_GET['id'])) {
    $get_ticket_id = $_GET['id'];

    $ticket_manager = new \AppTask\TicketManeger();
    $member_manager = new \AppTask\MemberManager();
    $comment_manager = new \AppTask\CommentManager();
    
    //チケットデータを取得
    $rec_ticket = $ticket_manager->GetTicket($get_ticket_id);
    //カテゴリ名を取得
    $rec_category = $ticket_manager->GetCategory($rec_ticket->category_id);
    //優先度名を取得
    $rec_priority = $ticket_manager->GetPrioriry($rec_ticket->priority_id);
    //作成者名を取得
    $rec_author = $member_manager->GetMemberName($rec_ticket->author_id, $project_id);
    //担当者名を取得
    $rec_charge = $member_manager->GetMemberName($rec_ticket->charge_id, $project_id);
    //状態名を取得
    $rec_status = $ticket_manager->GetStatus($rec_ticket->status_id);

    //全メンバー取得
    $rec_all_member = $member_manager->GetAllMember($project_id);
    //全状態を取得
    $rec_all_status = $ticket_manager->GetAllStatus();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-チケット</title>
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
                <input id="jump-button" type="button" name="jump-btn" value="ジャンプ">
            </form>
        </div>
        <div class="contents">
            <div class="block">
                <h4 class="block-title">■チケット詳細</h4>
                <table class="view-table">
                    <tbody>
                        <tr>
                            <th class="view-header"><span>ID</span></th>
                            <th class="view-header"><span>プロジェクト名</span></th>
                            <th class="view-header"><span>カテゴリ</span></th>
                            <th class="view-header"><span>優先度</span></th>
                            <th class="view-header"><span>期限</span></th>
                        </tr>
                        <tr>
                            <td class="view-item"><span id="ticket-id"><?php echo $rec_ticket->id; ?></span></td>
                            <td class="view-item"><span id="project_name"><?php echo $project_name; ?></span></td>
                            <td class="view-item"><span id="category"><?php echo $rec_category->category_name; ?></span></td>
                            <td class="view-item"><span id="priority"><?php echo $rec_priority->priority_name; ?></span></td>
                            <td class="view-item"><span id="deadline"><?php echo $rec_ticket->deadline; ?></span></td>
                        </tr>
                        <tr>
                            <th colspan="5" style="height: 2px;"></th>
                        </tr>
                        <tr>
                            <th class="view-header"><span>作成者</span></th>
                            <th class="view-header"><span>担当者</span></th>
                            <th class="view-header"><span>状態</span></th>
                            <th class="view-header"><span>追加日</span></th>
                            <th class="view-header"><span>最終更新日</span></th>
                        </tr>
                        <tr>
                            <td class="view-item">
                                <span id="author-name">
                                    <?php
                                    if ($rec_author != false) {
                                        echo $rec_author->member_name;
                                    } else {
                                        echo 'なし';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td class="view-item">
                                <span id="charge-name">
                                    <?php
                                    if ($rec_charge != false) {
                                        echo $rec_charge->member_name;
                                    } else {
                                        echo 'なし';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td class="view-item"><span id="status"><?php echo $rec_status->status_name; ?></span></td>
                            <td class="view-item"><span id="add-date"><?php echo $rec_ticket->add_date; ?></span></td>
                            <td class="view-item"><span id="last-updated"><?php echo $rec_ticket->last_updated; ?></span></td>
                        </tr>
                        <tr>
                            <th colspan="5" style="height: 2px;"></th>
                        </tr>
                        <tr>
                            <th class="view-header-title"><span>タイトル</span></th>
                            <td class="view-item-title" colspan="5"><span id="ticket-title"><?php echo $rec_ticket->ticket_title; ?></span></td>
                        </tr>
                        <tr>
                            <th class="view-header-content"><span>内容</span></th>
                            <td class="view-item-content" colspan="5">
                                <span id="ticket-content">
                                    <?php echo nl2br($rec_ticket->ticket_content); ?>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="block">
                <h4 class="block-title">■チケット操作</h4>
                <div class="ticket-control">
                    <?php echo '<a href="ticket_edit.php?id='.$rec_ticket->id.'"><button class="ctrl-button">このチケットを編集</button></a>'; ?>
                    <form class="ticket-control-form" action="ticket_charge_set.php" method="post">
                        <select class="ctrl-select" name="charge-id">
                            <?php
                            if ($rec_charge != false) {
                                echo '<option value="' . $rec_ticket->charge_id . '">' . $rec_charge->member_name . '</option>';
                            } else {
                                echo '<option value="0">なし</option>';
                            }
                            foreach ($rec_all_member as $member) {
                                echo '<option value="' . $member->id . '">' . $member->member_name . '</option>';
                            }
                            ?>
                        </select>
                        <?php echo '<input type="hidden" name="ticket-id" value="' . $rec_ticket->id . '">' ?>
                        <?php echo '<input type="hidden" name="status-id" value="' . $rec_ticket->status_id . '">' ?>
                        <input class="ctrl-button" type="submit" name="set-btn" value="担当者を設定">
                    </form>
                    <form class="ticket-control-form" action="ticket_status_set.php" method="post">
                        <select class="ctrl-select" name="status-id">
                            <?php
                            foreach ($rec_all_status as $status) {
                                echo '<option value="' . $status->id . '">' . $status->status_name . '</option>';
                            }
                            ?>
                        </select>
                        <?php echo '<input type="hidden" name="ticket-id" value="' . $rec_ticket->id . '">' ?>
                        <input class="ctrl-button" type="submit" name="set-button" value="状態を設定">
                    </form>
                </div>
            </div>
            <div class="block">
                <h4 class="block-title">■コメント</h4>
                <table class="comment-table">
                    <tbody>
                    <?php $comment_manager->OutputComment($project_id,$rec_ticket->id); ?>
                    </tbody>
                </table>
            </div>
            <div class="block">
                <h4 class="block-title">■コメント追加</h4>
                <form action="comment_add_check.php" method="post">
                    <?php echo '<input type="hidden" name="ticket-id" value="' . $rec_ticket->id . '">' ?>
                    <div class="form-item"><textarea id="input-comment" name="comment" cols="30" rows="10"></textarea></div>
                    <div class="form-item"><input id="input-add-button" type="submit" name="add-btn" value="追加"></div>
                </form>
            </div>
    </main>
    <footer>

    </footer>
</body>

</html>