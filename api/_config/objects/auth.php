<?php

class Auth {

    private $conn;
    private $perms = array();
    private $teams = array();
    private $roles = array();
    private $db_table_role = "role";
    private $db_view_user_team = "view_user_team";

    public $executor_id;

    public function __construct($db, $executor_id) {

        $this->conn = $db;
        $this->executor_id = $executor_id;

        $sql = "SELECT * FROM ".$this->db_view_user_team." WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->executor_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $perm_item = array(
                    "team" => $team_id,
                    "admin" => $role_admin,
                );

                array_push($this->teams, $team_id);
                array_push($this->roles, $role_admin);
                array_push($this->perms, $perm_item);
            }
        } else {
            return false;
        }

    }

    public function editRole($id) {

        $sameTeam = false;
        $teamAdmin = false;


        $sql = "SELECT * FROM ".$this->db_table_role." WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $team_id = $row['Team_ID'];
                if(array_search($team_id, $this->teams) !== FALSE){
                    $sameTeam = true;
                    foreach ($this->perms as $key => $val) {
                        if ($val['team'] === $team_id) {
                            if($val['admin']){
                                $teamAdmin = true;
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }

        if($sameTeam && $teamAdmin){
            return true;
        }

        return false;

    }

    public function editUser($id) {

        $sameTeam = false;
        $teamAdmin = false;

        $sql = "SELECT * FROM ".$this->db_view_user_team." WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if(array_search($team_id, $this->teams) !== FALSE){
                    $sameTeam = true;
                    foreach ($this->perms as $key => $val) {
                        if ($val['team'] === $team_id) {
                            if($val['admin']){
                                $teamAdmin = true;
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }

        if($sameTeam && $teamAdmin){
            return true;
        }

        return false;

    }

}
