<?php

namespace AppTask;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/timeline_manager.php';
require_once __DIR__ . '/ticket_manager.php';
require_once __DIR__ . '/member_manager.php';

//コメント管理クラス
class CommentManager
{
    //PDOインスタンス
    private $db;
    
    //コンストラクタ
    public function __construct()
    {
        try {
            $this->db = new \PDO(DSN, USER_NAME, PASSWORD);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //全コメント取得
    public function GetAllComment($project_id,$ticket_id)
    {
        $sql = "select * from comment where project_id=? and ticket_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $ticket_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //コメント取得
    public function GetComment($comment_id)
    {
        $sql = "select * from comment where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($comment_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }


    //コメント追加
    public function AddComment($project_id,$ticket_id,$member_id,$add_date,$content)
    {
        $sql = "insert into comment(project_id,ticket_id,author_id,add_date,comment_content) values(?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $ticket_id, $member_id, $add_date, $content));

        //タイムラインを追加
        $timeline_manager = new \AppTask\TimelineManager();
        $ticket_manager = new \AppTask\TicketManeger();

        $timeline_data = [];
        $timeline_data[] = $project_id;
        $timeline_data[] = $member_id;
        $timeline_data[] = $ticket_id;
        $timeline_data[] = $ticket_manager->GetActionID('comment');;
        $timeline_data[] = $add_date;
        
        $timeline_manager->AddTimeline($timeline_data);
    }

    //コメント更新
    public function UpdateComment($comment_id,$comment_content)
    {
        $sql = "update comment set comment_content=? where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($comment_content, $comment_id));
    }

    //コメント削除
    public function DeleteComment($comment_id)
    {
        $sql ="delete from comment where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($comment_id));
    }


    //コメント情報出力
    public function OutputComment($project_id,$ticket_id)
    {
        //データ取得
        $rec_comment = $this->GetAllComment($project_id,$ticket_id);

        //データなし
        if($rec_comment == false){
            echo '<div class="msg">コメントはありません。</div>';
        }else{
            foreach($rec_comment as $comment){
                //メンバーIDを取得
                $author_id = $comment->author_id;
                //メンバーIDからメンバー名を取得
                $member_manager = new \AppTask\MemberManager();
                $rec_member = $member_manager->GetMemberName($author_id, $project_id);

                //メンバー名の取得に失敗
                if($rec_member == false){
                    continue;
                }else{
                    $author_name = $rec_member->member_name;
                }

                //IDを取得
                $comment_id = $comment->id;
                //追加日を取得
                $add_date = $comment->add_date;
                //コメント内容を取得
                $content = $comment->comment_content;

                //HTMLを生成
                echo '<tr>';
                echo '<th class="comment-header">';
                echo '<div class="comment-info">';
                echo '<span class="comment-author">'.$author_name.'</span>';
                echo '<span class="comment-date">'.$add_date.'</span>';
                echo '</div>';
                echo '<div class="comment-ctrl">';
                echo '<form class="comment-form" action="comment_edit.php" method="post">';
                echo '<input type="hidden" name="ticket-id" value="'.$ticket_id.'">';
                echo '<input type="hidden" name="comment-id" value="'.$comment_id.'">';
                echo '<input class="comment-button" type="submit" name="edit-btn" value="編集">';
                echo '</form>';
                echo '<form class="comment-form" action="comment_delete.php" method="post">';
                echo '<input type="hidden" name="ticket-id" value="'.$ticket_id.'">';
                echo '<input type="hidden" name="comment-id" value="'.$comment_id.'">';
                echo '<input class="comment-button" type="submit" name="edit-btn" value="削除">';
                echo '</form>';
                echo '</div>';
                echo '</th>';
                echo '<td class="comment-content">';
                echo '<span class="comment">'.nl2br($content).'</span>';
                echo '</td>';
                echo '</tr>';
            }             
        }
    }
}