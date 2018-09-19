<?php namespace MariuszAnuszkiewicz\classes\RoundTeams;

use MariuszAnuszkiewicz\classes\Database\DB;
use MariuszAnuszkiewicz\classes\Teams\Teams;

class RoundTeams
{
    private $db;
    private $teams;
    private $array_fp;
    private $array_sp;
    private $first_column;
    private $second_column;
    private $glue;
    private $switch;
    private $keys;

    public function __construct()
    {
        $this->db = DB::getInstance();
        $this->teams = new Teams();
    }

    public function insertRoundsTeams($dataFP, $dataSP)
    {
        $sql = "INSERT INTO rounds_teams (team_first, team_second) VALUES (?, ?)";
        $this->db->query($sql, array($dataFP, $dataSP));
    }

    public function RoundTeamsArray()
    {
        foreach ($this->teams->selectFirstPart() as $team) {
            $this->array_fp[] = $team['name'];
        }
        foreach ($this->teams->selectSecondPart() as $team) {
            $this->array_sp[] = $team['name'];
        }

        $data = array_merge($this->array_fp, $this->array_sp);

        foreach ($data as $key => $value) {
            for ($i = 2; $i <= 4; $i++) {
                if ($i % 2 == 0) {
                    if ($key == $i) {
                        $this->glue[$key] = $value;
                        $switch = $this->switch ? null : $key;
                    }
                }
            }
            $switch;
        }

        $str = implode(" ", $this->array_sp);
        $split = explode(" ", $str);
        $double_split = (array_chunk($split, 2));

        for ($i = 0; $i < count($data) / 2; $i++) {

            if ($data[$i] != ($double_split[$i][1] . ' ' . $double_split[$i][0])) {
                $this->first_column[$i] = $data[$i];
                $this->second_column[$i] = $double_split[$i][0] . ' ' . $double_split[$i][1];
            }
            if ($data[$i] == ($double_split[$i][1] . ' ' . $double_split[$i][0])) {
                $this->keys[] = $i;

                if ($double_split[$i][1] . $double_split[$i][0] != $data[$i]) {
                    $this->first_column[$i] = $data[$i];

                    if ($i == $this->keys[0]) {
                        $this->first_column[$i] = $this->glue[$switch];
                    }
                    else if ($i == $this->keys[1]) {
                        $this->first_column[$i] = $this->glue[$switch / 2];
                    }
                    $this->second_column[$i] = $double_split[$i][0] . ' ' . $double_split[$i][1];
                }
            }
        }

        $firstColumn = implode("  ", $this->first_column);
        $secondColumn = implode("  ", $this->second_column);
        $exF = explode("  ", $firstColumn);
        $exS = explode("  ", $secondColumn);

        for ($i = 0; $i < count($this->first_column); $i++) {
            $this->insertRoundsTeams($exF[$i], $exS[$i]);
        }
    }

    public function initRoundTeamsTable()
    {
        if ($this->Count() > 0) {
            return null;
        } else {
            $this->RoundTeamsArray();
        }
    }

    public function Count()
    {
        $sql = "SELECT * FROM rounds_teams";
        $this->db->query($sql, null);
        $quantity = $this->db->countRow();
        return $quantity;
    }

    public function getRounds()
    {
        $sql = "SELECT * FROM rounds_teams";
        $this->db->query($sql, null);
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }
}