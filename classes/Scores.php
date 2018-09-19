<?php namespace MariuszAnuszkiewicz\classes\Scores;

use MariuszAnuszkiewicz\classes\Database\DB;
use MariuszAnuszkiewicz\classes\RoundTeams\RoundTeams;
use MariuszAnuszkiewicz\classes\Players\Players;
use MariuszAnuszkiewicz\classes\Results\Results;

class Scores
{
    private $db;
    private $roundTeams;
    private $players;
    private $results;
    private $roundsId;
    private $outputResult;
    private $outputWin;
    private $outputLoses;
    private $outputTeamsId;
    private $outputPkt;

    const UPDATE_VALUE = 1;
    const UPDATE_LIMIT = 1;
    const UPDATE_PKT = 1;

    public function __construct(RoundTeams $roundTeams = null , Players $players = null, Results $results = null)
    {
        $this->db = DB::getInstance();
        $this->roundTeams = $roundTeams;
        $this->players = $players;
        $this->results = $results;
    }

    public function getColumn($results, $dataKey, $resultKey)
    {

        foreach ($this->roundTeams->getRounds() as $rounds) {

            $sql = "SELECT * FROM teams WHERE name = ?";
            $i = 0;
            while ($i < $this->db->countRow()) {
                $this->db->query($sql, array($rounds[$dataKey]));
                $this->roundsId[] = $rounds['id'];
                $i++;
            }
            $TeamsResult = $this->db->results();
            for ($i = 0; $i < $this->db->countRow(); $i++) {
                $teams[] = $TeamsResult[$i][$resultKey];
            }
        }
        switch ($results) {
            case "id_rounds":
                return $this->roundsId;
                break;
            case "teams":
                return $teams;
                break;
        }
    }

    public function insertScoresTable($data = null, $id = null)
    {
        $outputResult_f = null;
        $outputResult_s = null;
        $teamsIdArray = array_merge($this->getColumn('teams', 'team_first', 'id'), $this->getColumn('teams', 'team_second', 'id'));
        $teamsStringArray[] = array_merge($this->getColumn('teams', 'team_first', 'name'), $this->getColumn('teams', 'team_second', 'name'));
        $roundsIdArray[] = $this->getColumn('id_rounds', 'team_first', 'id');
        $resultStringArray[] = array_merge($this->getResults('result'), $this->getResults('result'));

        $func = function ($value) {
            return $value;
        };

        $arrayLength = count($resultStringArray[0]);
        foreach ($resultStringArray as $val) {
            for ($i = 0; $i < $arrayLength; $i++) {
                if ($i < $arrayLength / 2) {
                    $outputResult_f[] = $val[$i];
                } else {
                    $fullResult = strlen($val[$i]);
                    $startSecondSegment = strstr($val[$i], " : ");
                    $lengthSecond = strlen($startSecondSegment) - 1;
                    $startFirstSegment = ($fullResult - $lengthSecond);
                    $secondSegment = substr($val[$i], $lengthSecond, $fullResult);
                    $firstSegment = substr($val[$i], 0, $startFirstSegment);
                    $outputResult_s[] = $secondSegment . ' : ' . $firstSegment;
                }
            }
            $outputResult[] = array_merge($outputResult_f, $outputResult_s);
        }

        for ($i = 0; $i < count($roundsIdArray); $i++) {
            $outputId = array_map($func, $roundsIdArray);
            $matchesId[] = $outputId[$i];
        }

        for ($i = 0; $i < count($teamsStringArray); $i++) {
            $outputStr = array_map($func, $teamsStringArray);
            $teamsStr[] = $outputStr[$i];
        }
        if ($id == 0) {
            $sql = "INSERT INTO scores (teams_fk, teams, rounds_teams_fk, result) VALUES (?, ?, ?, ?)";
            foreach ($teamsIdArray as $key => $team) {
                $this->db->query($sql, array($team, $teamsStr[0][$key], $matchesId[0][$key], $outputResult[0][$key]));
            }
        } else {

            $sql = "UPDATE scores SET result = ? WHERE teams_fk = ?";
            foreach ($this->results->getRoundsTeamsFromId($id) as $rounds) {

                $teamsId = $this->getTeamsIdFromRoundsTeams($rounds);
                $piece = explode(":", $data);
                $reverseData = $piece[1] . " : " . $piece[0];

                if ($teamsId[0]) {
                    $this->db->query($sql, array($data, $teamsId[0]));
                }
                if ($teamsId[1]) {
                    $this->db->query($sql, array($reverseData, $teamsId[1]));
                }
            }
        }
    }

    public function updateWinLoses($data, $key)
    {
        $sql = "SELECT * FROM scores WHERE teams_fk = ?";
        $this->db->query($sql, array($data));
        $row = $result = $this->db->results();

        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $this->outputResult[] = $row[$i]['result'];
            $this->outputWin[] = $row[$i]['win'];
            $this->outputLoses[] = $row[$i]['loses'];
            $this->outputTeamsId[] = $row[$i]['teams_fk'];
            $this->outputPkt[] = $row[$i]['pkt'];
        }
        if ($this->outputWin[$key] < self::UPDATE_LIMIT && $this->outputLoses[$key] < self::UPDATE_LIMIT) {
            $this->processUpdate($this->outputResult, $data, $this->outputTeamsId, $key);
        }
    }

    public function processUpdate(array $arrayData, $teamsId, $outputTeamsId, $key)
    {
        $updateLoses = $this->outputLoses[$key] + self::UPDATE_VALUE;
        $updateWins = $this->outputWin[$key] + self::UPDATE_VALUE;

        $results = null;
        for ($i = 0; $i < count($arrayData); $i++) {
            intval($outputTeamsId[$i]);
            $results[] = $arrayData[$i];
        }

        $args = explode(":", $results[$key]);
        $arg[] = $args;
        $splitArgs = array_chunk($arg,2);

        list($firstArg, $secondArg) = $splitArgs[0][0];
        $status = ((int) $firstArg < (int) $secondArg) ? "loses" : "win";
        $updateStatusWinLoses = ($status == "loses") ? $updateLoses : $updateWins;
        $updateStatusPkt = ($status == "win") ? $this->outputWin[$key] + self::UPDATE_PKT : null;


        $sql = "UPDATE scores SET {$status} = ?, pkt = ? WHERE teams_fk = ?";
        for ($i = 0; $i < count($arrayData); $i++) {
            $this->db->query($sql, array($updateStatusWinLoses, $updateStatusPkt, $teamsId));
        }
    }

    public function getResults($keyData)
    {
        $sql = "SELECT * FROM results";
        $this->db->query($sql, null);
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i][$keyData];
        }
        return $output;
    }

    public function getTeamsPkt()
    {
        $sql = "SELECT teams_fk FROM scores WHERE pkt > ?";
        $this->db->query($sql, array(0));
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getScores()
    {
        $sql = "SELECT * FROM scores";
        $this->db->query($sql, null);
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i];
        }
        return $output;
    }

    public function getTeamsIdFromRoundsTeams($data)
    {
        $sql = "SELECT * FROM scores WHERE rounds_teams_fk = ?";
        $this->db->query($sql, array($data));
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i]['teams_fk'];
        }
        return $output;
    }

    public function getTeamsToPlayer($data)
    {
        $sql = "SELECT teams FROM scores WHERE teams_fk = ?";
        $this->db->query($sql, array($data));
        $output = null;
        $row = $result = $this->db->results();
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i]['teams'];
        }
        $players = implode(" ", $output);
        return $pieces = explode(" ", $players);
    }

    public function splitTeamsToPlayer()
    {
        foreach ($this->getTeamsPkt() as $key => $teamPkt) {
            $team[$key] = $this->getTeamsToPlayer($teamPkt['teams_fk']);
            list($playerFirst, $playerSecond) = $team[$key];
            $playersFirstGroup[] = $playerFirst;
            $playersSecondGroup[] = $playerSecond;
        }
        return $allPlayers = array_merge($playersFirstGroup, $playersSecondGroup);
    }

    public function countScoresInPlayers()
    {
        $storeScores = [];
        $output = null;
        foreach ($this->splitTeamsToPlayer() as $key => $player) {
            $storeScores[] = $player;
        }
        foreach ($this->getTeamsPkt() as $idTeam) {
            $_SESSION['point'] = $this->getPointsByIdTeams($idTeam['teams_fk']);
        }
        $func = function($value) {
            return $value * $_SESSION['point'][0];
        };
        return $output = array_map($func, array_count_values($storeScores));
    }

    public function getPointsByIdTeams($data)
    {
        $sql = "SELECT pkt FROM scores WHERE teams_fk = ?";
        $this->db->query($sql, array($data));
        $row = $result = $this->db->results();
        $output = null;
        for ($i = 0; $i < $this->db->countRow(); $i++) {
            $output[] = $row[$i]['pkt'];
        }
        return $output;
    }

    public function Count()
    {
        $sql = "SELECT * FROM scores";
        $this->db->query($sql, null);
        $quantity = $this->db->countRow();
        return $quantity;
    }

    public function initScoresTable()
    {
        if ($this->Count() > 0) {
            foreach ($this->getScores() as $key => $round) {
               $this->updateWinLoses($round['teams_fk'], $key);
            }
            foreach ($this->countScoresInPlayers() as $key => $value) {
                $this->players->updatePkt($key, $value);
            }
        } else {
            $this->insertScoresTable();
        }
    }
}