<?php
$_SERVER["admin"] = true;
include_once "../includes/css_js.inc.php";
include_once "../includes/db.inc.php";
$dishes = getData();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //DISH toevogen
    if (isset($_POST['addDish'])) {
        $name = $_POST['dishName'] ?? '';
        $country = $_POST['country'] ?? '';
        $continent = $_POST['continent'] ?? '';
        $image = $_FILES['image']['name'] ?? '';

        if ($name && $country && $continent && $image) {
            move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $image);
            $db = connectToDB();
            $stmt = $db->prepare("INSERT INTO dishes (name, img_url, short_description, long_description) VALUES (:name, :img_url, :short_description, :long_description)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':img_url', $image);

            // Set default values for description fields if they don't exist
            $short_description = ""; // or a default message like "No description available"
            $long_description = "";
            $stmt->bindParam(':short_description', $short_description);
            $stmt->bindParam(':long_description', $long_description);

            $stmt->execute();

            $dishId = $db->lastInsertId();
            $stmt = $db->prepare("INSERT INTO country_has_dishes (country_id, dishes_id) VALUES (:country_id, :dish_id)");
            $stmt->bindParam(':country_id', $country);
            $stmt->bindParam(':dish_id', $dishId);
            $stmt->execute();
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
    <link rel="stylesheet" href="../dist/<?= $cssPath ?>" />
    <script type="module" src="../dist/<?= $jsPath ?>"></script>
</head>

<body>
    <main>
        <p class="icon-location2"></p>
        <header>
            <h1> Admin Pannel</h1>
        </header>

        <section>
            <h2>Dishes on the database</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Country</th>
                        <th>Continent</th>
                        <th>S info</th>
                        <th>L info</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dishes as $dish): ?>
                        <tr>
                            <td><?= htmlspecialchars($dish['dish']) ?></td>
                            <td><img src="<?= htmlspecialchars($dish['img']) ?>" alt="<?= htmlspecialchars($dish['dish']) ?>" width="50px" height="50px"></td>
                            <td><?= htmlspecialchars($dish['Country']) ?></td>
                            <td><?= htmlspecialchars($dish['Continent']) ?></td>
                            <td><?= htmlspecialchars($dish['S_description']) ?></td>
                            <td><?= htmlspecialchars($dish['L_description']) ?></td>

                            <td>
                                <!-- Edit Button -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="dishId" value="<?= htmlspecialchars($dish['id']) ?>">
                                    <input type="hidden" name="dishName" value="<?= htmlspecialchars($dish['dish']) ?>">
                                    <input type="hidden" name="country" value="<?= htmlspecialchars($dish['Country']) ?>">
                                    <input type="hidden" name="continent" value="<?= htmlspecialchars($dish['Continent']) ?>">
                                    <button type="submit" name="editDish">Edit</button>
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
                    $continents = getMenuOptions(); // continenten ophalen
                    foreach ($continents as $continent) {
                        echo "<option value=\"{$continent['id']}\">{$continent['name']}</option>";
                    }
                    ?>
                </select>

                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required>

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required>

                <button type="submit">Add Dish</button>
            </form>
        </section>
    </main>
</body>

</html>