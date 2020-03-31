<?php

namespace AppTask;

require_once __DIR__ . '/../config.php';

//メンバー管理クラス
class MemberManager
{
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

    //データ取得
    public function GetMember($project_id, $mail_address, $password)
    {
        $sql = "select * from member where project_id=? and mail_address=? and password=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $mail_address, $password));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //メンバー名取得
    public function GetMemberName($member_id, $project_id)
    {
        $sql = "select member_name from member where id=? and project_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($member_id, $project_id));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //メンバー名取得
    public function GetAllMember($project_id)
    {
        $sql = "select * from member where project_id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id));

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //データ追加
    public function AddMembar($project_id, $member_name, $mail_address, $password)
    {
        $sql = "insert into member(project_id,member_name,mail_address,password) values(?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_id, $member_name, $mail_address, $password));
    }
}
