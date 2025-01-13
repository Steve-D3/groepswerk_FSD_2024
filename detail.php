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

        @media (max-width: 749px) {
            body main {
                width: calc(95% - 1rem);
                /* Use most of the screen width with some padding */
                padding: 1rem;
            }

            body main .image_div {
                width: 100%;
                /* Take full width of the container */
                height: auto;
                display: flex;
                flex-direction: column;
                /* Stack items vertically */
                justify-content: center;
                align-items: center;
            }

            body main .image_div .img_slider {
                width: 100%;
                /* Full width for the image slider */
                height: auto;
                /* Maintain aspect ratio */
                max-width: 90%;
                /* Add a slight padding for aesthetics */
                max-height: 300px;
                /* Limit height for mobile screens */
            }

            body main .image_div img {
                width: 100%;
                /* Ensure images fill the slider */
                height: auto;
                /* Maintain proportions */
                object-fit: cover;
                /* Cover the available space proportionally */
            }

            body main .image_div a {
                position: relative;
                /* Inline with the content */
                margin: 0.5rem;
                /* Add spacing around buttons */
            }

            body main .titel_div,
            body main .description_div {
                width: 100%;
                /* Full width for text sections */
                text-align: center;
                /* Center-align text for readability */
            }

            body main .titel_div p {
                font-size: 18px;
                /* Slightly smaller text for mobile */
            }

            body main .description_div p {
                font-size: 14px;
                /* Adjust font size for readability on small screens */
            }

            body main .close_w a img {
                width: 20px;
                /* Smaller close button */
                height: 20px;

            }
        }


        @media (min-width: 750px) {
            body main {
                width: calc(60% - 2rem);
            }

            body main .image_div .img_slider {
                width: 50%;
                height: 50%;
            }
        }

        @media (min-width: 1000px) {
            body main {
                width: calc(75% - 2rem);
            }

            body main .image_div .img_slider {
                width: 60%;
                height: 60%;
            }
        }

        @media (min-width: 1200px) {
            body main {
                width: calc(60% - 2rem);
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
                        <?php
                        if (strpos($pic, "https") !== 0) {
                            $pic = './images/' . $pic; // Prepend "./images/" if not starting with "https"
                        }
                        ?>
                        <img src="<?= $pic; ?>" alt="">
                    <?php endforeach; ?>
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