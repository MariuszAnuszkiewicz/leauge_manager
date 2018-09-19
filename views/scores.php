<?php

use MariuszAnuszkiewicz\classes\Scores\Scores;

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', '../autoload/');
}
require_once(AUTOLOAD . "autoloading.php");
$scoresObj = new Scores();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../web/css/style-chrome.css" type="text/css" />
    <link rel="stylesheet" href="../web/css/style-firefox.css" type="text/css" />
    <title></title>
</head>
<body>

<div id="content">
  <table class="scores_table">
    <thead>
      <tr>
        <th>Teams</th>
        <th>Result</th>
        <th>Win</th>
        <th>Loses</th>
        <th>Pkt</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($scoresObj->getScores() as $score) :
      ?>
      <tr>
        <td><?= $score['teams'] ?></td>
        <td><?= $score['result'] ?></td>
        <td><?= $score['win'] ?></td>
        <td><?= $score['loses'] ?></td>
        <td><?= $score['pkt'] ?></td>
      </tr>
      <?php
        endforeach;
      ?>
    </tbody>
  </table>
</div>
 <a class="home-btn" href="../index.php">Home</a>
</body>
</html>