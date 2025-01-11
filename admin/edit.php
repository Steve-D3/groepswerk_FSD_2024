<?php

include_once "../includes/db.inc.php";


//Id van dish ophalen
$dishId = $_POST['dishId'] ?? null;

// echo "<p>Received dishId: [" . htmlspecialchars($dishId) . "] (type: " . gettype($dishId) . ")</p>";



if (!$dishId) {
  echo "geen id dish gevonden ";
  exit;
}

//Dish ID van index.php mathcen met de ID van DB
$dishes = getData();


foreach ($dishes as $dishItem) {
  if ((int)$dishItem['id'] === (int)$dishId) {
    $dish = $dishItem;
    break;
  }
}



if (!$dish) {
  echo "dish niet gevonden";
  exit;
}

// UPDATE DISH
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['short_description'], $_POST['long_description'], $_POST['img_url'])) {
  $name = (trim($_POST['name']));
  $shortDescription = (trim($_POST['short_description']));
  $longDescription = (trim($_POST['long_description']));
  $imgUrl = (trim($_POST['img_url']));

  try {
    // DB connectie 
    $db = connectToDB();
    $stmt = $db->prepare("
            UPDATE dishes
            SET name = :name, short_description = :short_description, long_description = :long_description, img_url = :img_url
            WHERE id = :id
        ");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':short_description', $shortDescription);
    $stmt->bindParam(':long_description', $longDescription);
    $stmt->bindParam(':img_url', $imgUrl);
    $stmt->bindParam(':id', $dishId, PDO::PARAM_INT);

    if ($stmt->execute()) {
      echo "<p style='color: green;'>Dish was updated sucesfully :)!</p>";
    } else {
      echo "<p style='color: red;'>Failed to update the dish loser >:v </p>";
    }
  } catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Dish</title>

  <style>

    :root {
      font-size: 62.5%;
      --soft-orange: hsl(35, 77%, 62%);
    }

    * {
      box-sizing: border-box;
    }

    /* General Reset */
    body {
      margin: 0;
      padding: 0;
      font-family: "MS Sans Serif", Arial, sans-serif;
      background-color: #008080;
      color: #000;


      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;

      h1 {
        text-align: center;
        font-size: 4rem;
        margin: 20px 0;
        color: #000;
        text-shadow: 1px 1px #fff;

      }

      form {
        width: 40%;
        margin: 20px auto;
        padding: 15px;
        background-color: #e0e0e0;
        border: 2px solid #808080;
        border-radius: 4px;
        box-shadow: inset -2px -2px #fff, inset 2px 2px #808080;
        font-size: 2rem;

        label {
          display: block;
          margin-bottom: 8px;
          font-weight: bold;
          color: #000;
        }

        input[type="text"],
        select {
          width: 100%;
          padding: 5px;
          margin-bottom: 12px;
          border: 2px solid #808080;
          background-color: #fff;
          box-shadow: inset -1px -1px #fff, inset 1px 1px #808080;
          font-size: 1.5rem;
          color: #000;
          outline: none;
        }

        input[type="text"]:focus,
        select:focus {
          border-color: #000080;
          box-shadow: inset -1px -1px #000, inset 1px 1px #808080;
        }

        button {
          display: block;
          width: 100%;
          padding: 8px;
          background-color: #c0c0c0;
          border: 2px solid #808080;
          box-shadow: -2px -2px #fff, 2px 2px #808080;
          color: #000;
          font-size: 1.5rem;
          font-weight: bold;
          text-align: center;
          cursor: pointer;
          outline: none;
          margin-top: 10px;
          transition: background-color 0.2s ease;

          &:hover {
            background-color: #a0a0a0;
          }

          &:active {
            box-shadow: inset 2px 2px #808080, inset -2px -2px #fff;
          }
        }
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      form {
        width: 90%;
      }

      form button {
        padding: 10px;
      }
    }
  </style>
</head>

<body>
  <h1>Edit Dish</h1>
  <form action="edit.php" method="post">
    <label for="name">Name</label>

    <input type="hidden" name="dishId" value="<?= ($dish['id']) ?>">

    <input type="text" name="name" id="name" value="<?= ($dish['dish']) ?>">
    <label for="short_description">Short Description</label>
    <input type="text" name="short_description" id="short_description" value="<?= ($dish['S_description']) ?>">
    <label for="long_description">Long Description</label>
    <input type="text" name="long_description" id="long_description" value="<?= ($dish['L_description']) ?>">
    <label for="img_url">Image URL</label>
    <input type="text" name="img_url" id="img_url" value="<?= ($dish['img']) ?>">
    <label for="country">Country</label>
    <input type="text" name="country" id="country" value="<?= ($dish['Country']) ?>">
    <label for="continent">Continent</label>
    <select name="continent" id="continent">
      <?php
      $continents = getMenuOptions();
      foreach ($continents as $continent) {
        echo "<option value=\"{$continent['id']}\">{$continent['name']}</option>";
      }
      ?>
    </select>
    <button type="submit">Save</button>
  </form>
</body>

</html>