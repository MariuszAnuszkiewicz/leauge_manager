<?php

use MariuszAnuszkiewicz\classes\Players\Players;

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', '../autoload/');
}
require_once(AUTOLOAD . "autoloading.php");
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
  <table class="players_table">
    <thead>
      <tr>
        <th>Players</th>
        <th>Points</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $playersObj = new Players();
        foreach ($playersObj->getPlayers() as $player) :
      ?>
      <tr>
        <td><?= $player['name'] ?></td>
        <td><?= $player['pkt'] ?></td>
        <td class="delete-player"><a href="../includes/delete-single.php?id=<?= $player['id'] ?>">Delete</a></td>
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