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

$filter_author = $_SESSION['filter-author'];
$filter_charge = $_SESSION['filter-charge'];
$filter_status = $_SESSION['filter-status'];
$filter_category = $_SESSION['filter-category'];
$filter_priority = $_SESSION['filter-priority'];
$filter_word = $_SESSION['filter-word'];

$member_manager = new \AppTask\MemberManager();
$ticket_manager = new \AppTask\TicketManeger();
$rec_member = $member_manager->GetAllMember($project_id);
$rec_status = $ticket_manager->GetAllStatus();
$rec_category = $ticket_manager->GetAllCategory();
$rec_priority = $ticket_manager->GetAllPrioriry();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-検索</title>
    <link rel="stylesheet" href="stylesheet/style_common.css">
    <link rel="stylesheet" href="stylesheet/style_05.css">
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
    <div class="user-name">ユーザー:<?php echo $member_name; ?></div>
    <div class="jump-form">
        <form action="ticket_jump_check.php" method="post">
            <input id="input-ticket-num" type="text" name="ticket-num">
            <input id="jump-button" type="button" name="jump-btn" value="ジャンプ">
        </form>
    </div>
    <main>
        <div class="contents">
            <div class="block">
	            <form action="ticket_filter_set.php" method="post">
		            <h4 class="block-title">■フィルタリング</h4>
		            <table class="filter-table">
			            <tbody>
				            <tr>
					            <th class="filter-header"><span>作成者</span></th>
					            <th class="filter-header"><span>担当者</span></th>
					            <th class="filter-header"><span>状態</span></th>
					            <th class="filter-header"><span>カテゴリー</span></th>
					            <th class="filter-header"><span>優先度</span></th>
				            </tr>
				            <tr>
					            <td class="filter-item">
						            <select class="filter-select" name="author">
                                        <option value="0">どれでも</option>
                                        <?php
                                        foreach($rec_member as $member){
                                            if($member->id == $filter_author){
                                                echo '<option value="'.$member->id.'" selected>'.$member->member_name.'</option>';
                                            }else{
                                                echo '<option value="'.$member->id.'">'.$member->member_name.'</option>';
                                            }
                                        }
                                        ?>
						            </select>
					            </td>
					            <td class="filter-item">
						            <select class="filter-select" name="charge">
                                        <option value="0">どれでも</option>
                                        <?php
                                        foreach($rec_member as $member){
                                            if($member->id == $filter_charge){
                                                echo '<option value="'.$member->id.'" selected>'.$member->member_name.'</option>';
                                            }else{
                                                echo '<option value="'.$member->id.'">'.$member->member_name.'</option>';
                                            }
                                        }
                                        ?>
						            </select>
					            </td>
					            <td class="filter-item">
						            <select class="filter-select" name="status">
                                        <option value="0">どれでも</option>
                                        <?php
                                        foreach($rec_status as $status){
                                            if($status->id == $filter_status){
                                                echo '<option value="'.$status->id.'" selected>'.$status->status_name.'</option>';
                                            }else{
                                                echo '<option value="'.$status->id.'">'.$status->status_name.'</option>';
                                            }
                                        }
                                        ?>
						            </select>
					            </td>
					            <td class="filter-item">
						            <select class="filter-select" name="category">
                                        <option value="0">どれでも</option>
                                        <?php
                                        foreach($rec_category as $category){
                                            if($category->id == $filter_category){
                                                echo '<option value="'.$category->id.'" selected>'.$category->category_name.'</option>';
                                            }else{
                                                echo '<option value="'.$category->id.'">'.$category->category_name.'</option>';
                                            }
                                        }
                                        ?>
						            </select>
					            </td>
					            <td class="filter-item">
						            <select class="filter-select" name="priority">
                                        <option value="0">どれでも</option>
                                        <?php
                                        foreach($rec_priority as $priority){
                                            if($priority->id == $filter_priority){
                                                echo '<option value="'.$priority->id.'" selected>'.$priority->priority_name.'</option>';
                                            }else{
                                                echo '<option value="'.$priority->id.'">'.$priority->priority_name.'</option>';
                                            }
                                        }
                                        ?>
						            </select>
					            </td>
				            </tr>
			            </tbody>
		            </table>
		            <div class="key-word">
                        <?php
                        if(($filter_word != '') && ($filter_word != null)){
                            echo '<input class="filter-key-word" type="text" name="key-word" placeholder="キーワードを入力" value="'.$filter_word.'">';
                        }else{
                            echo '<input class="filter-key-word" type="text" name="key-word" placeholder="キーワードを入力">';
                        }
                        ?>
			            
			            <input class="filter-button" type="submit" name="filter-btn" value="フィルターを適用">
		            </div>
	            </form>
            </div>
            <div class="block">
                <h4 class="block-title">■チケット一覧</h4>
                <table class="list-table">
                    <tbody>
                        <?php $ticket_manager->OutputTicketList($project_id,$filter_author,$filter_charge,$filter_status,$filter_category,$filter_priority,$filter_word); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer>

    </footer>
</body>

</html>