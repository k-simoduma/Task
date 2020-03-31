<?php

namespace AppTask;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/member_manager.php';

//タイムライン管理クラス
class TimelineManager
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

    //タイムラインを取得
    public function GetTimeline($project_id)
    {
        $sql = "select * from timeline where project_id=? order by date_time desc,id limit 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //動作文を取得
    public function GetActionSentence($id)
    {
        $sql = "select action_sentence from action where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($id));

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //タイムラインを追加
    public function AddTimeline($data_array)
    {
        $sql = "insert into timeline(project_id,member_id,ticket_id,action_id,date_time) values(?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data_array);
    }

    //タイムラインの表示用htmlを出力
    public function OutputTimeline($project_id)
    {
        //データベースからタイムラインを取得
        $rec_timeline = $this->GetTimeline($project_id);

        //データなし
        if ($rec_timeline == false) {
            echo '<span>まだ活動していません。</span>';
        } else {
            foreach ($rec_timeline as $timeline) {
                //メンバーIDを取得
                $member_id = $timeline->member_id;
                //メンバーIDからメンバー名を取得
                $member_manager = new \AppTask\MemberManager();
                $rec_member = $member_manager->GetMemberName($member_id, $project_id);
                $member_name = $rec_member->member_name;

                //メンバー名の取得に失敗
                if (($member_name == '') || ($member_name == null)) {
                    continue;
                }

                //動作IDから動作文を取得
                $action_id = $timeline->action_id;
                $rec_action_sentece = $this->GetActionSentence($action_id);
                $action_sentence = $rec_action_sentece['action_sentence'];

                //動作文の取得に失敗
                if (($action_sentence == '') || ($action_sentence == null)) {
                    continue;
                }

                //チケットIDを取得
                $ticket_id = $timeline->ticket_id;
                //日時を取得
                $date_time = $timeline->date_time;

                //出力
                echo '<div class="history">';
                echo '<span class="date-time">' . $date_time . '</span>';
                echo '<div class="action">' . $member_name . 'が<a class="ticket-link" href="ticket_view.php?id=' . $ticket_id . '">ID-' . $ticket_id . '</a>' . $action_sentence . '</div>';
                echo '</div>';
            }
        }
    }
}
