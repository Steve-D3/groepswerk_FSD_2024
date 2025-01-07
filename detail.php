<?php
include_once "includes/css_js.inc.php";
include_once "includes/db.inc.php";
$id = @$_GET["id"];
$data = getData();
$dish;

foreach ($data as $elem) {
    if ($elem["id"] == $id) {
        $dish = $elem;
    }
}

$extraPics = getExtraImages($id);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dish description</title>
    <script type="module" src="./dist/<?= $jsPath ?>"></script>
    <style>
        body {
            height: 100vh;
            background-color: #008080;

            display: flex;
            justify-content: center;
            align-items: center;

            margin: 0;
            padding: 0;

            font-family: "MS Sans Serif", Arial, sans-serif;

            main {
                height: calc(60% - 2rem);
                width: calc(85% - 2rem);
                padding: 2rem;

                display: grid;
                grid-template-columns: 50% 50%;
                grid-template-rows: repeat(3, 1fr);

                background-color: #818181;
                border: 2px solid #ffffff;
                /* box-shadow: inset -2px -2px 0px #808080, inset 2px 2px 0px #000000; */

                position: relative;

                .image_div {
                    grid-row: 1 / -1;
                    grid-column: 1 / 2;
                    width: 100%;
                    height: 100%;

                    background-color: #c3c3c3;
                    box-shadow: inset -2px -2px 0px #808080, inset 2px 2px 0px #000000;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    position: relative;

                    .img_slider {
                        display: flex;
                        width: 350px;
                        height: 350px;
                        box-shadow: 4px 4px 0px #000000, -4px -4px 0px #ffffff;
                        transition: transform 0.5s ease-in-out;

                        overflow: hidden;

                        .img_holder {
                            display: flex;
                            transition: transform 0.5s ease-in-out;

                            img {
                                height: 100%;
                                width: 100%;
                                object-fit: fill;


                            }
                        }
                    }

                    a {
                        position: absolute;
                        cursor: pointer;

                        img {
                            width: 40px;
                            height: 40px;
                            box-shadow: none;
                        }

                        &:first-of-type {
                            right: 0;
                        }

                        &:last-of-type {
                            left: 0;
                        }
                    }
                }

                .titel_div {
                    margin: auto;
                    height: 3rem;
                    width: 80%;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    p {
                        margin: 0;
                        width: 100%;
                        background-color: #c3c3c3;

                        color: #010081;
                        padding: 1rem 1rem;

                        text-align: center;
                        font-weight: 600;
                        font-size: 24px;

                        box-shadow: inset -2px -2px 0px #808080, inset 2px 2px 0px #000000;
                    }
                }

                .description_div {
                    margin: 0;
                    grid-row: 2 / 4;


                    display: flex;
                    justify-content: center;
                    align-items: center;

                    p {
                        background-color: #c3c3c3;
                        width: calc(80% - 2rem);
                        height: 80%;

                        padding: 1rem;
                        box-shadow: inset -2px -2px 0px #808080, inset 2px 2px 0px #000000;
                    }
                }

                .close_w {
                    position: absolute;
                    right: 0;

                    img {
                        width: 25px;
                        height: 25px;
                    }

                }

            }
        }
    </style>
</head>

<body>

    <main>
        <div class="image_div">
            <div class="img_slider">
                <div class="img_holder">
                    <!-- <img src="<?php echo $dish["img"]; ?>" alt=""> -->
                    <?php foreach ($extraPics as $pic): ?>
                        <img src="<?= $pic; ?>" alt="">
                    <? endforeach; ?>
                </div>
            </div>
            <a id="next_btn">
                <img src="images/next.svg" alt="Next">
            </a>
            <a id="prev_btn">
                <img src="images/prev.svg" alt="Previous">
            </a>
        </div>

        <div class="titel_div">
            <p><?= $dish["dish"] ?></p>
        </div>
        <div class="description_div">
            <p>
                <?= $dish["L_description"] ?>
            </p>
        </div>

        <div class="close_w">
            <a href="index.php"><img src="images/close.svg" alt="close_window_icon"></a>
        </div>
    </main>

</body>

</html>