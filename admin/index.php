<?php
$_SERVER["admin"] = true;
include_once "../includes/css_js.inc.php";
include_once "../includes/db.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN HOMEPAGE</title>
    <link rel="stylesheet" href="../dist/<?= $cssPath ?>" />
    <script type="module" src="../dist/<?= $jsPath ?>"></script>
</head>

<body>
    <?= "php works on admin page" ?>
    <p class="icon-location2"></p>
    <header>
    <h1> Admin Pannel hola</h1>
    </header>
    <img src="images/sample.jpg" alt="">
</body>

</html>