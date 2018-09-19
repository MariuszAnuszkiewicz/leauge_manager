<?php

use MariuszAnuszkiewicz\classes\Results\Results;

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

<div id="content_results">
  <table class="results_table">
    <thead>
      <tr>
        <th>Id</th>
        <th>Teams Id</th>
        <th>Result</th>
      </tr>
    </thead>
      <tbody>
      <?php
        $resultsObj = new Results();
        foreach ($resultsObj->getResults() as $result) :
      ?>
      <tr>
        <td><?= $result['id'] ?></td>
        <td><?= $result['rounds_teams_fk'] ?></td>
        <td><?= $result['result'] ?></td>
        <td class="upd-result"><a href="../includes/update-single.php?id=<?= $result['id'] ?>">Update Result</a></td>
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