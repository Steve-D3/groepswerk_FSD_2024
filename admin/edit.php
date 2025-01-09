<?php

include_once "../includes/db.inc.php";


//Id van dish ophalen
$dishId = $_POST['dishId'] ?? null;

echo "<p>Received dishId: [" . htmlspecialchars($dishId) . "] (type: " . gettype($dishId) . ")</p>";



if (!$dishId){
    echo "geen id dish gevonden 2";
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



?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dish</title>
 </head>
 <body>
    <h1>Edit Dish</h1>
    <form action="edit.php" method="post">
        <label for="name">Name</label>

        <input type="hidden" name="dishId" value="<?= htmlspecialchars($dish['id']) ?>">

        <input type="text" name="name" id="name" value="<?= htmlspecialchars($dish['dish']) ?>">
        <label for="short_description">Short Description</label>
        <input type="text" name="short_description" id="short_description" value="<?= htmlspecialchars($dish['S_description']) ?>">
        <label for="long_description">Long Description</label>
        <input type="text" name="long_description" id="long_description" value="<?= htmlspecialchars($dish['L_description']) ?>">
        <label for="img_url">Image URL</label>
        <input type="text" name="img_url" id="img_url" value="<?=htmlspecialchars($dish['img'])?>">
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
