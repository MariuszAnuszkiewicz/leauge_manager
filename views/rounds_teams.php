<?php

use MariuszAnuszkiewicz\classes\RoundTeams\RoundTeams;

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
    <table class="ronunds_teams_table">
      <thead>
        <tr>
          <th class="label-first-team">First Team</th>
          <th class="label-second-team">Second Team</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $roundTeamsObj = new RoundTeams();
        foreach ($roundTeamsObj->getRounds() as $round) :
            ?>
            <tr>
              <td class="team-first"><?= $round['team_first']  ?></td>
              <td class="vs"><?= " vs. " ?></td>
              <td class="team-second"><?= $round['team_second'] ?></td>
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