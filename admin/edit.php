<?php

include_once "../includes/db.inc.php";


//Id van dish ophalen
$dishId = $_POST['dishId'] ?? null;

// echo "<p>Received dishId: [" . htmlspecialchars($dishId) . "] (type: " . gettype($dishId) . ")</p>";



if (!$dishId){
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



if(!$dish){
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
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/edit.css">
    <link rel="stylesheet" href="./css/icons.css">
    <title>Edit Dish</title>

    <style>
    @import url("./reset.css");
@import url("./icons.css");
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap");

:root {
  font-size: 62.5%;
  --soft-orange: hsl(35, 77%, 62%);
}

* {
  box-sizing: border-box;
}

body {
  min-width: 300px;
  height: 100vh;
  background-color: #008080; 
  padding: 2rem;

  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

h1 {
  color: #fdffff;
  margin-bottom: 2rem;
  text-align: center;
font-size: 3.6rem;
}

form {

  background-color:white;
  max-height: 80vh;  
  overflow-y: auto;  
  padding: 3rem 4rem;
  width: 60%;  
  max-width:600px;  
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form label {
  font-size: 1.8rem;
  color: #010081;
  font-weight: bold;
}

form input,
form textarea,
form select {
  width: 100%;
  padding: 1.5rem;
  margin: 1.2rem 0;
  border: 1px solid #c3c3c3;
  font-size: 1.6rem;
}

form input#img_url {
  height: 100px;  
  font-size: 2rem;  
  text-align: center;  
}

form select {
  font-size: 1.6rem;
}

button {
  width: 100%;
  padding: 2rem;  
  font-size: 2.2rem;  
  font-weight: bold;
  color: white;
  background-color:rgb(126, 200, 129);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  text-align: center;
}

button:hover {
  background-color: #45a049;
}

button:active {
  background-color:rgb(25, 198, 172);
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
        <input type="text" name="img_url" id="img_url" value="<?=($dish['img'])?>">
        <label for="country">Country</label>
        <select name="country" id="country">
            <option value="1">test</option>
            <option value="2">test</option>
            <option value="3">test</option>
        </select>
        <label for="continent">Continent</label>
        <select name="continent" id="continent">
            <option value="1">test</option>
            <option value="2">test</option>
            <option value="3">test</option>
        </select>
        <button type="submit">Save</button>
    </form>
 </body>
 </html>
