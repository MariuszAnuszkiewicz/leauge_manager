<?php

if (!defined('AUTOLOAD')) {
    define('AUTOLOAD', '../autoload/');
}
require_once(AUTOLOAD . "autoloading.php");

use MariuszAnuszkiewicz\classes\Results\Results;
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
<h2>Edit results</h2>
<div class="update-result">
    <form action="" id="result-form" method="post">
        <?php
        $objResults = new Results();
        foreach ($objResults->getById($_GET['id']) as $row) : ?>
            <?php
            if (isset($row)) {
                ?>
                <label for="lab-id"><b>Id</b></label>
                <input type="text" name="id" id="id-upd" value="<?= $row['id'] ?>">
                <label for="lab-result"><b>Result</b></label>
                <input type="text" name="result" id="result-upd" value="<?= $row['result'] ?>">
                <?php
                break;
            }
            ?>
        <?php endforeach; ?>

        <input type="submit" name="submit" id="save_btn" value="Save">
    </form>
</div>

<?php
  if (isset($_POST['result'])) {
      Run::updateSingle($_POST['submit'], $_POST['result'], $_GET['id']);
  }
?>
</body>
</html>

