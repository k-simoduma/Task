<?php

namespace AppTask;

require_once __DIR__ . '/../config.php';

//プロジェクト管理クラス
class ProjectManager
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

    //データ取得(プロジェクト名指定)
    public function GetProject($project_name)
    {
        $sql = "select * from project where project_name=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_name));

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    //データ取得
    public function GetAllProject()
    {
        $sql = "select * from project order by id desc";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    //データ追加
    public function AddProject($project_name)
    {
        $sql = "insert into project(project_name) values(?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($project_name));
    }
}
