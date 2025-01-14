<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

include_once "includes/css_js.inc.php";
include_once "includes/db.inc.php";


$selectedContinent = isset($_GET['continent_id']) ? intval($_GET['continent_id']) : null;
$data = getData($selectedContinent);

echo "<pre>";
// print_r($data[0]);
echo "</pre>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISHES</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>" />
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <main>
        <section class="section1">
            <a href="index.php"><h2>Dishes from the world</h2></a>
            <div>
                <ul>
                    <?php foreach ($menuOptions as $option): ?>
                        <li><a href="index.php?continent_id=<?= $option["id"] ?>"><?= $option["name"] ?></a></li>
                    <? endforeach; ?>
                </ul>
            </div>
        </section>

        <section class="section2">
            <ul>
                <?php foreach ($data as $elem): ?>
                    <li>
                        <a href="detail.php?id=<?= $elem["id"]; ?>"><?= $elem["dish"]; ?></a>
                        <?php
                            $imgPath = $elem["img"];
                            if (strpos($imgPath, "../") === 0){
                                $imgPath = './'. substr($imgPath, 3);
                            }
                        ?>
                        <img src=<?= $imgPath; ?> alt="">
                        <p><?= $elem["S_description"] ?></p>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
        
        <a href="./admin/login.php" class="admin_button">Admin</a>
    </main>

</body>

</html>