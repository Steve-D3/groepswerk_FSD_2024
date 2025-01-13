<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../includes/db.inc.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dishId = $_POST['dishId'] ?? 0;

    if (isset($_POST['uploadImages']) && isset($_FILES['images'])) {
        $images = $_FILES['images'];
        $uploadDir = '../images/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }


        for ($i = 0; $i < count($images['name']); $i++) {
            $imageName = basename($images['name'][$i]);
            $tempPath = $images['tmp_name'][$i];
            $imagePath = $uploadDir . $imageName;

            if ($images['error'][$i] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($tempPath, $imagePath)) {
                    $db = connectToDB();
                    $stmt = $db->prepare("INSERT INTO dish_images (dish_id, img_url) VALUES (:dish_id, :img_url)");
                    $stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);
                    $stmt->bindParam(':img_url', $imageName, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    } elseif (isset($_POST['deleteImage'])) {
        $imageId = $_POST['image_id'] ?? 0;


        $db = connectToDB();
        $stmt = $db->prepare("SELECT img_url FROM dish_images WHERE id = :id");
        $stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        $image = $stmt->fetch();

        if ($image) {
            $imagePath = "../images/" . $image['img_url'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $stmt = $db->prepare("DELETE FROM dish_images WHERE id = :image_id");
            $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}

$dishId = $_POST['dishId'] ?? $_GET['dishId'] ?? 0;
$db = connectToDB();
$stmt = $db->prepare("SELECT id, img_url FROM dish_images WHERE dish_id = :dish_id");
$stmt->bindParam(':dish_id', $dishId, PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extra images</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #008080;

            color: #000;
            margin: 0;
            padding: 0;

            .container {
                max-width: 900px;
                margin: 2rem auto;
                padding: 1rem;
                background-color: #e0e0e0;
                box-shadow: inset -2px -2px 0px #808080, inset 2px 2px 0px #000000;
                border: 2px solid #808080;

                position: relative;

                h1 {
                    text-align: center;
                    font-size: 1.5rem;
                    margin-bottom: 1rem;
                    background-color: #c3c3c3;
                    padding: 0.5rem;
                    border: 2px solid #808080;
                    box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                }

                .images-section,
                .upload-section {
                    margin: 2rem 0;
                    padding: 1rem;
                    border: 2px solid #808080;
                    box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                    background-color: #d0d0d0;

                    h2 {
                        font-size: 1.2rem;
                        margin-bottom: 1rem;
                        color: #000;
                    }
                }

                .images-section {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    gap: 1rem;
                    margin: 2rem 0;
                    padding: 1rem;
                    border: 2px solid #808080;
                    box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                    background-color: #d0d0d0;

                    h2 {
                        grid-column: 1 / -1;
                        /* Make the heading span the entire width */
                        font-size: 1.2rem;
                        margin-bottom: 1rem;
                        color: #000;
                    }

                    .image-preview {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        background-color: #e0e0e0;
                        border: 2px solid #808080;
                        box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                        padding: 0.5rem;

                        img {
                            width: 100%;
                            /* Make the image fit the grid cell */
                            height: 150px;
                            object-fit: cover;
                            /* Maintain aspect ratio, crop excess */
                            border: 2px solid #808080;
                            box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                            margin-bottom: 0.5rem;
                        }

                        form {
                            button {
                                background-color: #c3c3c3;
                                color: #000;
                                border: 2px solid #808080;
                                padding: 0.3rem 0.6rem;
                                font-size: 0.8rem;
                                cursor: pointer;
                                box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;

                                &:hover {
                                    background-color: #d0d0d0;
                                }

                                &:active {
                                    border: 2px solid #000;
                                    border-top-color: #808080;
                                    border-left-color: #808080;
                                    background-color: #a0a0a0;
                                    box-shadow: none;
                                }
                            }
                        }
                    }

                    p {
                        grid-column: 1 / -1;
                        text-align: center;
                        font-size: 1rem;
                        color: #000;
                    }
                }


                .upload-section {
                    form {
                        display: flex;
                        flex-direction: column;
                        gap: 1rem;

                        input[type="file"] {
                            margin: 1rem 0;
                            background-color: #c3c3c3;
                            border: 2px solid #808080;
                            padding: 0.2rem;
                            box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            font-size: 0.9rem;
                        }

                        button {
                            background-color: #c3c3c3;
                            color: #000;
                            border: 2px solid #808080;
                            padding: 0.5rem 1rem;
                            font-size: 0.9rem;
                            cursor: pointer;
                            box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;

                            &:hover {
                                background-color: #d0d0d0;
                                color: #000;
                            }

                            &:active {
                                border: 2px solid #000;
                                border-top-color: #808080;
                                border-left-color: #808080;
                                background-color: #a0a0a0;
                                box-shadow: none;
                            }
                        }
                    }
                }

                .close_w {
                    position: absolute;
                    top: 0;
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

    <div class="container">
        <h1>Extra Images for Dish ID: <?= htmlspecialchars($dishId) ?></h1>

        <!-- Section 1: Existing Images -->
        <section class="images-section">
            <h2>Existing Images</h2>
            <?php if ($images): ?>
                <?php foreach ($images as $image): ?>
                    <div class="image-preview">
                        <?php
                        $imagePath = (strpos($image['img_url'], 'http') === 0)
                            ? $image['img_url'] : (strpos($image['img_url'], '../images/') === false
                                ? '../images/' . $image['img_url'] : $image['img_url']);
                        ?>
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Dish Image">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="dishId" value="<?= htmlspecialchars($dishId) ?>">
                            <input type="hidden" name="image_id" value="<?= htmlspecialchars($image['id']) ?>">
                            <button type="submit" name="deleteImage">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No extra images found for this dish.</p>
            <?php endif; ?>
        </section>

        <!-- Section 2: Upload New Images -->
        <section class="upload-section">
            <h2>Add New Images</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="dishId" value="<?= htmlspecialchars($dishId) ?>">
                <input type="file" name="images[]" multiple accept="image/*">
                <button type="submit" name="uploadImages">Upload</button>
            </form>
        </section>
        <div class="close_w">
            <a href="index.php"><img src="../images/close.svg" alt="close_window_icon"></a>
        </div>
    </div>

</body>

</html>