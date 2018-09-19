<?php namespace MariuszAnuszkiewicz\classes\Teams;

use MariuszAnuszkiewicz\classes\Database\DB;

class Teams
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }
    public function createTmpTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS tmp_teams_combinations (
                  id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                  team_first VARCHAR(125) NOT NULL,
                  team_second VARCHAR(125) NOT NULL
                )ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->db->query($sql, null);
        return $this->db->getExecute();
    }

    public function dropTmpTable()
    {
        $sql = "DROP TABLE tmp_teams_combinations";
        $this->db->query($sql, null);
        return $this->db->getExecute();
    }

    public function initInsertTeams($teamF, $teamS)
    {
        $sql = "INSERT INTO tmp_teams_combinations (team_first, team_second) VALUES (?, ?)";
        return $this->db->query($sql, array($teamF, $teamS));
    }

    public function insertDataTable()
    {
        $sql = "SELECT * FROM tmp_teams_combinations";
        $this->db->query($sql, null);
        $this->db->getExecute();

        if ($this->db->countRow() > 0) {
            $this->insertTeams();
        }
    }

    public function insertTeams()
    {
        $status = true;
        $sql = "INSERT INTO teams (name) VALUES (?)";
        foreach ($this->getNonDoubleRows() as $row) {
                $this->db->query($sql, array($row['team_first']));
                $status = true;
        }
        return $status;
    }

    public function initDataTable($teamF, $teamS)
    {
        $sql = "SELECT * FROM teams";
        $this->db->query($sql, null);
        $this->db->getExecute();

        if ($this->db->countRow() < 1) {
            $this->createTmpTable();
            $this->initInsertTeams($teamF, $teamS);
        }

        if ($this->db->countRow() > 1) {
            $this->dropTmpTable();
        }
    }

    public function getTeams()
    {
        $sql = "SELECT * FROM teams";
        $this->db->query($sql, null);
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getNonDoubleRows()
    {
        $sql = "SELECT team_first
                FROM tmp_teams_combinations
                GROUP BY team_first
                HAVING COUNT(id) > 1;";
        $this->db->query($sql, null);
        $row = $result = $this->db->results();
        $output = null;
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function Count()
    {
        $sql = "SELECT name FROM teams";
        $this->db->query($sql, null);
        $quantity = $this->db->countRow();
        $q = $quantity / 2;
        return $q;
    }

    public function selectFirstPart()
    {
        $q = $this->Count();
        $sql = "SELECT name FROM teams LIMIT $q";
        $this->db->query($sql, array());
        $row = $result = $this->db->results();
        $output = null;
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function selectSecondPart()
    {
        $q = $this->Count();
        $sql = "SELECT name FROM teams ORDER BY id DESC LIMIT $q";
        $this->db->query($sql, array());
        $row = $result = $this->db->results();
        $output = null;
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getId($name)
    {
        $sql = "SELECT id FROM teams WHERE name = ? ";
        $this->db->query($sql, array($name));
        $result = $this->db->results();
        foreach($result as $row) {
            if(isset($result[0])) {
                return $row['id'];
            }
            return false;
        }
    }

    public function getTeamsById($id)
    {
        $sql = "SELECT name FROM teams WHERE id = ?";
        $this->db->query($sql, array($id));
        $result = $this->db->results();
        foreach($result as $row) {
            if(isset($result[0])) {
                return $row['name'];
            }
            return false;
        }
    }
}