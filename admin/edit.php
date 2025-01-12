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
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #008080;

      margin: 0;
      padding: 0;

      section {
        width: 80%;
        max-width: 600px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #d0d0d0;
        border: 2px solid #808080;
        box-shadow: inset -2px -2px 0px #fff, inset 2px 2px 0px #000;

        position: relative;

        h1 {
          font-size: 1.5rem;
          text-align: center;
          margin-bottom: 1rem;
          color: #000;
        }

        form {
          display: flex;
          flex-direction: column;
          gap: 1rem;

          label {
            font-size: 1.2rem;
            font-weight: 700;
            color: #000;
          }

          input[type="text"],
          select {
            padding: 0.5rem;
            font-size: 0.9rem;
            color: #000;
            background-color: #fff;
            border: 2px solid #808080;
            box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
            outline: none;

            &:focus {
              border-color: #000;
            }
          }

          select {
            height: 3rem;
          }

          button {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            color: #000;
            background-color: #c3c3c3;
            border: 2px solid #808080;
            box-shadow: inset -1px -1px 0px #fff, inset 1px 1px 0px #000;
            cursor: pointer;

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


    @media (max-width: 480px) {
      section {
        width: 95%;
        /* Take up more of the screen */
        padding: 0.8rem;
        /* Less padding */

        h1 {
          font-size: 1.1rem;
          /* Adjusted title font */
        }

        form {
          label {
            font-size: 0.8rem;
            /* Smaller font for labels */
          }

          input[type="text"],
          select {
            font-size: 0.8rem;
            /* Smaller inputs */
            padding: 0.4rem;
          }

          button {
            font-size: 0.8rem;
            /* Smaller button font */
            padding: 0.4rem 0.8rem;
          }
        }

        .close_w {
          top: 5px;
          right: 5px;

          img {
            width: 18px;
            height: 18px;
          }
        }
      }
    }
  </style>
</head>

<body>
  <section>

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
    <div class="close_w">
      <a href="index.php"><img src="../images/close.svg" alt="close_window_icon"></a>
    </div>
  </section>
</body>

</html>