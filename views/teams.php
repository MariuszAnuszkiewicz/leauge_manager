<?php

use MariuszAnuszkiewicz\classes\Teams\Teams;

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', '../autoload/');
}
require_once(AUTOLOAD . "autoloading.php");
$teamsObj = new Teams();
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
  <table class="teams_table">
    <thead>
      <tr>
        <th>Teams</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($teamsObj->getTeams() as $team) :
      ?>
      <tr>
        <td><?= $team['name'] ?></td>
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