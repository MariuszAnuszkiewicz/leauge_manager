<?php namespace MariuszAnuszkiewicz\classes\Players;

use MariuszAnuszkiewicz\classes\Database\DB;

class Players
{
    private $db;
    const LIMIT_ADD_PLAYERS = 3;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function insertPlayer($data)
    {
        if ($this->limitAddPlayer() == true) {
            $sql = "INSERT INTO players (name) VALUES (?)";
            return $this->db->query($sql, array($_POST[$data]));
        }
    }

    public function getPlayers()
    {
        $sql = "SELECT * FROM players";
        $this->db->query($sql, null);
        $result = $this->db->results();
        $output = null;
        foreach($result as $row) {
            $output[] = $row;
        }
        return $output;
    }

    public function updatePkt($data, $value)
    {
        $sql = "UPDATE players SET pkt = ? WHERE name = ?";
        $this->db->query($sql, array($value, $data));
    }

    public function limitAddPlayer()
    {
        $status = true;
        $sql = "SELECT * FROM players";
        $this->db->query($sql, null);
        $this->db->getExecute();

        if ($this->db->countRow() > self::LIMIT_ADD_PLAYERS) {
            echo "Nie można dodać więcej niż " . (self::LIMIT_ADD_PLAYERS + 1) . " użytkowników";
            $status = false;
        }
        return $status;
    }

    public function getPlayersById($id)
    {
        $sql = "SELECT * FROM players WHERE id = ?";
        $this->db->query($sql, array($id));
        $result = $this->db->results();
        $output = null;
        foreach($result as $row) {
            $output[] = $row;
        }
        return $output;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM players WHERE id = ?";
        $this->db->query($sql, array($id));
        $this->db->getExecute();
    }
}


