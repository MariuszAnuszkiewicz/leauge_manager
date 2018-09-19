<?php namespace MariuszAnuszkiewicz\classes\Run;

use MariuszAnuszkiewicz\classes\Combination\Combination;
use MariuszAnuszkiewicz\classes\Players\Players;
use MariuszAnuszkiewicz\classes\ValidateInput\ValidateInput;
use MariuszAnuszkiewicz\classes\Results\Results;
use MariuszAnuszkiewicz\classes\Scores\Scores;
use MariuszAnuszkiewicz\classes\RoundTeams\RoundTeams;

class Run
{

    public static function init()
    {
        $objPlayers = new Players();
        $objResults = new Results();
        $objScores = new Scores(new RoundTeams, new Players, new Results);

        $objResults->initResultsTable();
        $objScores->initScoresTable();

        $arrayData = $objPlayers->getPlayers();
        Combination::init($arrayData);
        $objValidate = new ValidateInput();

        if (isset($_POST['submit_btn'])) {
            if ($objValidate->validate(self::escape($_POST['add_player']))) {
                $objPlayers->insertPlayer('add_player');
            }
        }
    }

    public static function updateSingle($submit, $input, $getId)
    {
        $objResults = new Results();
        $objScores = new Scores(new RoundTeams, new Players, new Results);
        if (isset($submit)) {
            $objResults->updateResults($input, $getId);
            $objScores->insertScoresTable($input, $getId);
            header('location: ../views/results.php');
        }
    }

    public static function deleteSingle($submit, $id)
    {
        $objPlayers = new Players();
        if (isset($submit)) {
            $objPlayers->delete($id);
            header('location: ../views/players.php');
        }
    }

    public static function escape($data) {
        return htmlentities($data, ENT_QUOTES);
    }
}