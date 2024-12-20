<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include_once "includes/css_js.inc.php";
include_once "includes/db.inc.php";


$data = getData();

echo "<pre>";
print_r($data);
echo "</pre>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEBSITE HOMEPAGE</title>
    <link rel="stylesheet" href="./dist/<?= $cssPath ?>" />
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
</head>

<body>
    <main>
        <section class="section1">
            <h2>Title</h2>
            <div>
                <ul>
                    <li>test 1</li>
                    <li>test 2</li>
                    <li>test 3</li>
                    <li>test 4</li>
                </ul>
            </div>
        </section>

        <section class="section2">
            <ul>
                <li>
                    <a href="#">food1</a>
                    <!-- img -->
                </li>
                <li>
                    <a href="#">food2</a>
                    <!-- img -->
                </li>
                <li>
                    <a href="#">food3</a>
                    <!-- img -->
                </li>
                <li>
                    <a href="#">food4</a>
                    <!-- img -->
                </li>
                <li>
                    <a href="#">food5</a>
                    <!-- img -->
                </li>
            </ul>
        </section>

    </main>

</body>

</html>