<?php namespace MariuszAnuszkiewicz\classes\Results;

use MariuszAnuszkiewicz\classes\Database\DB;

class Results
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function selectFromRoundsTeams()
    {
        $sql = "SELECT * FROM rounds_teams";
        $this->db->query($sql, array());
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function insertToResult()
    {
        $sql = "INSERT INTO results (rounds_teams_fk) VALUES (?)";
        foreach ($this->selectFromRoundsTeams() as $row) {
            $this->db->query($sql, array($row['id']));
        }
    }

    public function getResults()
    {
        $sql = "SELECT * FROM results";
        $this->db->query($sql, null);
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM results WHERE id = ?";
        $this->db->query($sql, array($id));
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getRoundsTeamsFromId($id)
    {
        $sql = "SELECT * FROM results WHERE id = ?";
        $this->db->query($sql, array($id));
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i]['rounds_teams_fk'];
        }
        return $output;
    }

    public function Count()
    {
        $sql = "SELECT * FROM results";
        $this->db->query($sql, null);
        $quantity = $this->db->countRow();
        return $quantity;
    }

    public function initResultsTable()
    {
        if ($this->Count() > 0) {
            return null;
        } else {
            $this->insertToResult();
        }
    }

    public function updateResults($data, $id)
    {
        $sql = "UPDATE results SET result = ? WHERE id = ?";
        return $this->db->query($sql, array($data, $id));
    }
}