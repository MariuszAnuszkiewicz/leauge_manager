<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./web/css/style-chrome.css" type="text/css" />
    <link rel="stylesheet" href="./web/css/style-firefox.css" type="text/css" />
    <title></title>
</head>
<body>
<nav class="menu">
    <ul class="active">
        <li class="current-item"><a href="index.php">Home</a></li>
        <li><a href="views/players.php">Players</a></li>
        <li><a href="views/teams.php">Teams</a></li>
        <li><a href="views/rounds_teams.php">RoundsTeams</a></li>
        <li><a href="views/results.php">Results</a></li>
        <li><a href="views/scores.php">Scores</a></li>
    </ul>
</nav>
<?php

use MariuszAnuszkiewicz\classes\Run\Run;

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', './autoload/');
}
require_once(AUTOLOAD . "autoloading.php");
require_once(FORMS . "add_player.html");

 Run::init();

?>
</body>
</html>