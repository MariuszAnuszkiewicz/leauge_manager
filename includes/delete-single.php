<?php

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', '../autoload/');
}
require_once(AUTOLOAD . "autoloading.php");

use MariuszAnuszkiewicz\classes\Players\Players;
use MariuszAnuszkiewicz\classes\Run\Run;

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
<div class="delete-result">
    <form action="" id="delete-form" method="post">
      <?php
        $playersObj = new Players();
            foreach ($playersObj->getPlayersById($_GET['id']) as $player) : ?>
                <h4><?= $player['name']; ?></h4>
      <?php endforeach; ?>
      <input type="submit" name="submit" id="delete_btn" value="Delete">
    </form>
</div>
 <a class="home-btn" href="../index.php">Home</a>
<?php
if (isset($_POST['submit'])) {
    Run::deleteSingle($_POST['submit'], $_GET['id']);
}
?>
</body>
</html>

