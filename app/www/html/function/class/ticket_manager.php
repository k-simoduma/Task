<?php

namespace AppTask;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/ticket.php';
require_once __DIR__ . '/timeline_manager.php';

//チケット管理クラス
class TicketManeger
{
    //状態ID
    const STATUS_ID = array(
        'new' => '1',
        'charge' => '2',
        'verified' => '3',
        'progress' => '4',
        'feedback' => '5',
        'done' => '6'
    );

    //動作ID
    const ACTION_ID = array(
        'create' => '1',
        'edit' => '2',
        'charge' => '3',
        'comment' => '4',
        'status' => '5'
    );

    //出力モード
    const OUTPUT_MODE_ID = array(
        'charge' => 1,
        'new' => 2,
        'done' => 3,
        'datatime' => 4
    );

    //最大表示数
    const MYPAGE_DISP_NUM_MAX = 10;
    const FILTER_DISP_NUM_MAX = 30;

    //PDOインスタンス
    private $db;

    //HTML生成(マイページ用)
    private function CreateTicketHTML($ticket_id, $ticket_title, $last_updated, $status_id, $priority_id)
    {
        $ret_data = [];
        $style = '';

        //スタイル取得
        switch ($status_id) {
            case self::STATUS_ID['new']:
                $style = 'style="background-color: #FF6666;"';
                break;
            case self::STATUS_ID['charge']:
                $style = 'style="background-color: #66CCFF;"';
                break;
            case self::STATUS_ID['verified']:
                $style = 'style="background-color: #FF9900;"';
                break;
            case self::STATUS_ID['progress']:
                $style = 'style="background-color: #EEEEEE;"';
                break;
            case self::STATUS_ID['feedback']:
                $style = 'style="background-color: #660099;"';
                break;
            case self::STATUS_ID['done']:
                $style = 'style="background-color: #66CC33;"';
                break;
            default:
                $style = 'style="background-color: #EEEEEE;"';
                break;
        }

        $ret_data[] = '<tr class="ticket">';
        $ret_data[] = '<th class="ticket-num">';
        $ret_data[] = '<div><a href="ticket_view.php?id=' . $ticket_id . '" title="' . $ticket_title . '">ID-' . $ticket_id . '</a></div>';
        $ret_data[] = '<div><a href="ticket_edit.php?id=' . $ticket_id . '" title="編集"><img src="image/edit.png" alt="編集"></a></div>';
        $ret_data[] = '</th>';
        $ret_data[] = '<td class="ticket-title" ' . $style . '>';
        $ret_data[] = '<div class="title">' . $ticket_title . '</div>';
        $ret_data[] = '<span class="last-updated">最終更新日：' . $last_updated . '</span>';
        $ret_data[] = '</td>';
        $ret_data[] = '</tr>';

        return $ret_data;
    }

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

    //キー名を指定して状態IDを取得
    public function GetStatusID($key)
    {
        //指定されたキーが存在しない
        if (array_key_exists($key, self::STATUS_ID) == false) {
            return self::STATUS_ID['new'];
        }

        return self::STATUS_ID[$key];
    }

    //キー名を指定して状態IDを取得
    public function GetActionID($key)
    {
        //指定されたキーが存在しない
        if (array_key_exists($key, self::ACTION_ID) == false) {
            return self::ACTION_ID['create'];
        }

        return self::ACTION_ID[$key];
    }

    //キー名を指定して出力モード番号を取得
    public function GetOutputModeID($key)
    {
        //指定されたキーが存在しない
        if (array_key_exists($key, self::OUTPUT_MODE_ID) == false) {
            return self::OUTPUT_MODE_ID['charge'];
        }

        return self::OUTPUT_MODE_ID[$key];
    }

    //カテゴリー取得
    public function GetCategory($category_id)
    {
        $sql = "select category_name from category where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($category_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //優先度取得
    public function GetPrioriry($priority_id)
    {
        $sql = "select priority_name from priority where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($priority_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //カテゴリー取得
    public function GetAllCategory()
    {
        $sql = "select * from category where 1";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //優先度取得
    public function GetAllPrioriry()
    {
        $sql = "select * from priority where 1";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //状態取得
    public function GetStatus($status_id)
    {
        $sql = "select status_name from status where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($status_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //状態取得
    public function GetAllStatus()
    {
        $sql = "select * from status where 1";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //チケット取得
    public function GetTicket($ticket_id)
    {
        $sql = "select * from ticket where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($ticket_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //全チケット取得
    public function GetAllTicket($project_id)
    {
        $sql = "select * from ticket where project_id=? order by last_updated desc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //チケット取得(担当者指定)
    public function GetChargeTicket($project_id, $charge_id)
    {
        $sql = "select * from ticket where project_id=? and charge_id=? order by last_updated desc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $charge_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //チケット取得(状態指定)
    public function GetStatusTicket($project_id, $status_id)
    {
        $sql = "select * from ticket where project_id=? and status_id=? order by last_updated desc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $status_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetFilterTicket($project_id,$author_id,$charge_id,$status_id,$category_id,$priority_id,$key_word)
    {
        $where = " where project_id=".$project_id;
        $like = "";

        if($author_id != 0){
            $where = $where." and author_id=".$author_id;
        }

        if($charge_id != 0){
            $where = $where." and charge_id=".$charge_id;
        }

        if($status_id != 0){
            $where = $where." and status_id=".$status_id;
        }

        if($category_id != 0){
            $where = $where." and category_id=".$category_id;
        }

        if($priority_id != 0){
            $where = $where." and priority_id=".$priority_id;
        }

        if(($key_word != '') && ($key_word != null)){
            $like = " and ticket_title like '%".$key_word."%'";
        }

        $sql = "select * from ticket".$where.$like;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    //新規チケット追加
    public function AddTicket(&$ticket_obj)
    {
        $data = [];

        //チケットを追加
        $sql = "insert into ticket(project_id,author_id,charge_id,add_date,last_updated,status_id,category_id,priority_id,deadline,ticket_title,ticket_content) values(?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $data[] = $ticket_obj->project_id;
        $data[] = $ticket_obj->author_id;
        $data[] = $ticket_obj->charge_id;
        $data[] = $ticket_obj->add_date;
        $data[] = $ticket_obj->last_updated;
        $data[] = $ticket_obj->status_id;
        $data[] = $ticket_obj->category_id;
        $data[] = $ticket_obj->priority_id;
        $data[] = $ticket_obj->deadline;
        $data[] = $ticket_obj->title;
        $data[] = $ticket_obj->content;
        $stmt->execute($data);

        //タイムラインを追加
        $timeline_manager = new \AppTask\TimelineManager();

        $timeline_data = [];
        $timeline_data[] = $ticket_obj->project_id;
        $timeline_data[] = $ticket_obj->author_id;
        $timeline_data[] = $this->db->lastInsertId();
        $timeline_data[] = self::ACTION_ID['create'];
        $timeline_data[] = $ticket_obj->add_date;

        $timeline_manager->AddTimeline($timeline_data);
    }

    //チケット更新
    public function UpdateTicket($project_id,$member_id,$ticket_id,$last_updated,$category_id,$priority_id,$deadline,$ticket_title,$ticket_content)
    {
        $sql = "update ticket set last_updated=?,category_id=?,priority_id=?,deadline=?,ticket_title=?,ticket_content=? where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($last_updated,$category_id,$priority_id,$deadline,$ticket_title,$ticket_content,$ticket_id));

        //タイムラインを追加
        $timeline_manager = new \AppTask\TimelineManager();
        $timeline_data = [];
        $timeline_data[] = $project_id;
        $timeline_data[] = $member_id;
        $timeline_data[] = $ticket_id;
        $timeline_data[] = self::ACTION_ID['edit'];
        $timeline_data[] = $last_updated;

        $timeline_manager->AddTimeline($timeline_data);
    }

    //チケットの担当者を更新
    public function UpdateTicketCharge($project_id, $member_id, $ticket_id, $charge_id)
    {
        date_default_timezone_set("Asia/Tokyo");
        $datetime = new \DateTime();

        $sql = "update ticket set charge_id=?,last_updated=? where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($charge_id, $datetime->format('Y-m-d H:i:s'), $ticket_id));

        //タイムラインを追加
        $timeline_manager = new \AppTask\TimelineManager();
        $timeline_data = [];
        $timeline_data[] = $project_id;
        $timeline_data[] = $member_id;
        $timeline_data[] = $ticket_id;
        $timeline_data[] = self::ACTION_ID['charge'];
        $timeline_data[] = $datetime->format('Y-m-d H:i:s');

        $timeline_manager->AddTimeline($timeline_data);
    }

    //チケットの状態を更新
    public function UpdateTicketStatus($ticket_id, $status_id)
    {
        date_default_timezone_set("Asia/Tokyo");
        $datetime = new \DateTime();

        $sql = "update ticket set status_id=?,last_updated=? where id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($status_id, $datetime->format('Y-m-d H:i:s'), $ticket_id));
    }

    //チケット情報出力(マイページ用)
    public function OutputTicketMyPage($mode_id, $project_id, $member_id)
    {
        //モードに対応したチケットをデータベースから取得
        switch ($mode_id) {
            case self::OUTPUT_MODE_ID['charge']:
                $rec_ticket = $this->GetChargeTicket($project_id, $member_id);
                break;

            case self::OUTPUT_MODE_ID['new'];
                $rec_ticket = $this->GetStatusTicket($project_id, self::STATUS_ID['new']);
                break;

            case self::OUTPUT_MODE_ID['done'];
                $rec_ticket = $this->GetStatusTicket($project_id, self::STATUS_ID['done']);
                break;

            case self::OUTPUT_MODE_ID['datetime'];
                $rec_ticket = $this->GetAllTicket($project_id);
                break;

            default:
                return;
        }

        //データなし
        if ($rec_ticket == false) {
            return;
        } else {
            //カウンタ
            $count = 0;

            //データ文ループ
            foreach ($rec_ticket as $ticket) {
                //最大数だったら終了
                if ($count === self::MYPAGE_DISP_NUM_MAX) {
                    break;
                }

                //チケットID取得
                $ticket_id = $ticket->id;
                //チケットタイトル取得
                $ticket_title = $ticket->ticket_title;
                //最終更新日取得
                $last_updated = $ticket->last_updated;
                //状態ID取得
                $status_id = $ticket->status_id;
                //プライオリティIDを取得
                $priority_id = $ticket->priority_id;

                $data = [];
                $data = $this->CreateTicketHTML($ticket_id, $ticket_title, $last_updated, $status_id, $priority_id);

                //出力
                foreach ($data as $value) {
                    echo $value;
                }
                $count += 1;
            }
        }
    }

    //チケット情報出力(一覧用)
    public function OutputTicketList($project_id,$author_id,$charge_id,$status_id,$category_id,$priority_id,$key_word)
    {
        $rec_ticket = $this->GetFilterTicket($project_id,$author_id,$charge_id,$status_id,$category_id,$priority_id,$key_word);

        if($rec_ticket == false){
            echo '<div class="msg">該当するチケットはありません。</div>';
            return;
        }else{
            foreach($rec_ticket as $ticket){
                //チケットID取得
                $ticket_id = $ticket->id;
                //チケットタイトル取得
                $ticket_title = $ticket->ticket_title;
                //最終更新日取得
                $last_updated = $ticket->last_updated;
                //状態ID取得
                $status_id = $ticket->status_id;
                //プライオリティIDを取得
                $priority_id = $ticket->priority_id;

                $data = [];
                $data = $this->CreateTicketHTML($ticket_id, $ticket_title, $last_updated, $status_id, $priority_id);

                //出力
                foreach ($data as $value) {
                    echo $value;
                }
            }
        }
    }
}
