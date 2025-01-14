<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    // Redirect to login page if not authenticated
    header("Location: ../admin/login.php");
    exit();
}
include_once "../includes/css_js.inc.php";
include_once "../includes/db.inc.php";
$dishes = getData();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // DISH toevoegen
    if (isset($_POST['addDish'])) {
        // Fetch and sanitize form inputs
        $name = $_POST['dishName'] ?? '';
        $countryName = $_POST['country'] ?? ''; // Assuming country is input as a name
        $continent = $_POST['continent'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $short_description = $_POST['s_description'] ?? '';
        $long_description = $_POST['l_description'] ?? '';

        // Validate required fields
        if ($name && $countryName && $continent && $image) {
            // Ensure the image file was uploaded successfully
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = "../images/";
                $uploadPath = $uploadDir . basename($image);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Connect to the database
                    $db = connectToDB();

                    try {
                        // Check if the country already exists in the database
                        $stmt = $db->prepare("SELECT id FROM country WHERE name = :country AND continent_id = :continent_id");
                        $stmt->bindParam(':country', $countryName);
                        $stmt->bindParam(':continent_id', $continent);
                        $stmt->execute();
                        $country = $stmt->fetch(PDO::FETCH_ASSOC);

                        // If the country doesn't exist, insert it
                        if (!$country) {
                            $stmt = $db->prepare("INSERT INTO country (name, continent_id) VALUES (:name, :continent_id)");
                            $stmt->bindParam(':name', $countryName);
                            $stmt->bindParam(':continent_id', $continent);
                            $stmt->execute();
                            $countryId = $db->lastInsertId();
                        } else {
                            $countryId = $country['id'];
                        }

                        // Insert the dish into the `dishes` table
                        $stmt = $db->prepare("
                        INSERT INTO dishes (name, img_url, short_description, long_description) 
                        VALUES (:name, :img_url, :short_description, :long_description)
                    ");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':img_url', $uploadPath);
                        $stmt->bindParam(':short_description', $short_description);
                        $stmt->bindParam(':long_description', $long_description);
                        $stmt->execute();

                        // Get the last inserted dish ID
                        $dishId = $db->lastInsertId();

                        // Link the dish to the country
                        $stmt = $db->prepare("
                        INSERT INTO country_has_dishes (country_id, dishes_id) 
                        VALUES (:country_id, :dish_id)
                    ");
                        $stmt->bindParam(':country_id', $countryId);
                        $stmt->bindParam(':dish_id', $dishId);
                        $stmt->execute();

                        echo "Dish added successfully!";
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Failed to move uploaded image to the target directory.";
                }
            } else {
                echo "Error uploading image: " . $_FILES['image']['error'];
            }
        } else {
            echo "Please fill in all required fields (Name, Country, Continent, Image).";
        }
    }

    //Dish updaten
    if (isset($_POST['updateDish'])) {
        $id = $_POST['dishId'] ?? '';
        $name = $_POST['dishName'] ?? '';
        $country = $_POST['country'] ?? '';
        $continent = $_POST['continent'] ?? '';

        if ($id && $name && $country && $continent) {
            $db = connectToDB();
            $stmt = $db->prepare("UPDATE dishes SET name = :name WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $stmt = $db->prepare("UPDATE country_has_dishes SET country_id = :country_id WHERE dishes_id = :dish_id");
            $stmt->bindParam(':country_id', $country);
            $stmt->bindParam(':dish_id', $id);
            $stmt->execute();
        }
    }

    // DISH verwijderen
    if (isset($_POST['deleteDish'])) {
        $id = $_POST['dishId'] ?? '';

        if ($id) {
            $db = connectToDB();
            $db->beginTransaction();
            try {
                // entires eerst verwijderen uit country_has_dishes
                $stmt = $db->prepare("DELETE FROM country_has_dishes WHERE dishes_id = :dish_id");
                $stmt->bindParam(':dish_id', $id);
                $stmt->execute();

                // Delete dish
                $stmt = $db->prepare("DELETE FROM dishes WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                $db->commit();
            } catch (PDOException $e) {
                $db->rollBack();
                echo "Error: " . $e->getMessage();
            }
        }
    }

    // NA SUBMISSIE PAGINA REGRESHEN
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN HOMEPAGE</title>
    <link rel="stylesheet" href="./css/style.css">
    <script type="module" src="../dist/<?= $jsPath ?>"></script>
</head>

<body>
    <main>
        <!-- <p class="icon-location2"></p> -->
        <header>
            <h1> Admin Pannel</h1>
        </header>

        <section>
            <h2>Dishes on the database</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Country</th>
                        <th>Continent</th>
                        <th>Short Description</th>
                        <th>Long Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dishes as $dish): ?>
                        <tr>
                            <td><?= htmlspecialchars($dish['dish']) ?></td>
                            <td><img src="<?= htmlspecialchars($dish['img']) ?>" alt="<?= htmlspecialchars($dish['dish']) ?>" width="75px" height="75px"></td>
                            <td><?= htmlspecialchars($dish['Country']) ?></td>
                            <td><?= htmlspecialchars($dish['Continent']) ?></td>
                            <td>
                                <div><?= htmlspecialchars($dish['S_description']) ?></div>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($dish['L_description']) ?></div>
                            </td>
                            <td class="buttons">
                                <!-- Edit Button -->
                                <form method="POST" action="edit.php" style="display:inline;">
                                    <input type="hidden" name="dishId" value="<?= htmlspecialchars($dish['id']) ?>">
                                    <input type="hidden" name="dishName" value="<?= htmlspecialchars($dish['dish']) ?>">
                                    <input type="hidden" name="country" value="<?= htmlspecialchars($dish['Country']) ?>">
                                    <input type="hidden" name="continent" value="<?= htmlspecialchars($dish['Continent']) ?>">
                                    <button type="submit" name="editDish">Edit</button>
                                </form>

                                <form method="POST" action="extraImages.php" style="display:inline;">
                                    <input type="hidden" name="dishId" value="<?= htmlspecialchars($dish['id']) ?>">
                                    <button type="submit" name="extraImages">Images</button>
                                </form>

                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="dishId" value="<?= htmlspecialchars($dish['id']) ?>">
                                    <button type="submit" name="deleteDish" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>


        <section>
            <h2>Add New Dish</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="addDish" value="1">

                <label for="dishName">Name:</label>
                <input type="text" id="dishName" name="dishName" required>

                <label for="continent">Continent:</label>
                <select id="continent" name="continent" required>
                    <?php
                    $continents = getMenuOptions();
                    foreach ($continents as $continent) {
                        echo "<option value=\"{$continent['id']}\">{$continent['name']}</option>";
                    }
                    ?>
                </select>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required>

                <label for="s_description">Short Description</label>
                <textarea name="s_description" id="s_description"></textarea>

                <label for="l_description">Long Description</label>
                <textarea name="l_description" id="l_description"></textarea>
                <br>

                <button type="submit">Add Dish</button>
            </form>
        </section>

        <a href="logout.php" class="admin_button">Back</a>
    </main>
</body>

</html>